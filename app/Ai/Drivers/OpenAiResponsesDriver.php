<?php

namespace App\Ai\Drivers;

use App\Ai\Contracts\StreamableChatDriver;
use App\Ai\Data\ChatRequest;
use App\Ai\Data\ChatResponse;
use App\Ai\Data\ToolCall;
use App\Ai\Exceptions\ProviderException;
use App\Ai\Support\ProviderHttp;
use App\Ai\Support\PartialJson;
use App\Ai\Support\ProviderSse;
use App\Ai\Support\ProviderToolSupport;
use App\Ai\Support\StreamToolProgress;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

final class OpenAiResponsesDriver implements StreamableChatDriver
{
    public function __construct(private readonly string $provider, private readonly array $config) {}

    public function complete(ChatRequest $request): ChatResponse
    {
        $response = $this->send($request, false);
        if ($response->failed()) {
            throw ProviderException::http($this->provider, $response->status(), $response->body());
        }

        return $this->present($response->json(), $request);
    }

    public function stream(ChatRequest $request, callable $onEvent): ChatResponse
    {
        $response = $this->send($request, true);
        $completed = null;
        $progress = new StreamToolProgress;
        $items = [];
        ProviderSse::consume($this->provider, $response, function (array $frame) use (&$completed, &$items, $progress, $onEvent): void {
            $event = json_decode($frame['data'], true);
            if (! is_array($event)) return;
            $type = $event['type'] ?? $frame['event'];
            if ($type === 'response.output_text.delta') {
                $onEvent(['type' => 'text_delta', 'text' => (string) ($event['delta'] ?? '')]);
            }
            $index = (int) ($event['output_index'] ?? 0);
            if ($type === 'response.output_item.added' && ($event['item']['type'] ?? '') === 'function_call') {
                $items[$index] = $event['item'];
            }
            if ($type === 'response.function_call_arguments.delta' && isset($items[$index])) {
                $items[$index]['arguments'] = ($items[$index]['arguments'] ?? '').($event['delta'] ?? '');
            }
            if (isset($items[$index])) {
                $item = $items[$index];
                $progress->emitStart($index, $item['call_id'] ?? $item['id'] ?? '', $item['name'] ?? '', $item['arguments'] ?? '', $onEvent);
            }
            if ($type === 'response.output_item.done' && ($event['item']['type'] ?? '') === 'function_call') {
                $item = $event['item'];
                $call = ToolCall::fromArray([
                    'id' => $item['call_id'] ?? $item['id'] ?? '',
                    'name' => $item['name'] ?? '',
                    'arguments' => $item['arguments'] ?? '{}',
                ]);
                $onEvent(['type' => 'tool_end', 'call' => [
                    'id' => $call->id,
                    'name' => $call->name,
                    'arguments' => PartialJson::endPaintArgs($call->name, $call->arguments),
                ]]);
            }
            if (in_array($type, ['response.completed', 'response.incomplete'], true)) {
                $completed = $event['response'] ?? null;
            }
            if (in_array($type, ['error', 'response.failed'], true)) {
                throw new ProviderException((string) data_get($event, 'response.error.message', data_get($event, 'error.message', $event['message'] ?? 'Responses API failed.')), $this->provider);
            }
        });
        if (! is_array($completed)) {
            throw new ProviderException('Responses API stream ended without a final response.', $this->provider);
        }

        return $this->present($completed, $request);
    }

    private function send(ChatRequest $request, bool $stream): Response
    {
        $key = trim((string) ($this->config['api_key'] ?? ''));
        if ($key === '') throw ProviderException::missingKey($this->provider);
        $payload = [
            'model' => $request->apiModel,
            'instructions' => $request->fullSystem(),
            'input' => array_map(fn ($message) => $message->toArray(), $request->messages),
            'stream' => $stream,
            'store' => false,
        ];
        if (isset($request->options['max_tokens'])) {
            $payload['max_output_tokens'] = $request->options['max_tokens'];
        }
        if ($request->wantsTools()) {
            $payload['tools'] = array_map(static fn (array $tool) => [
                'type' => 'function',
                'name' => $tool['function']['name'],
                'description' => $tool['function']['description'] ?? '',
                'parameters' => $tool['function']['parameters'] ?? ['type' => 'object', 'properties' => (object) []],
                'strict' => false,
            ], $request->tools);
            $forced = ProviderToolSupport::forcedToolName($request->toolChoice);
            $payload['tool_choice'] = $forced ? ['type' => 'function', 'name' => $forced] : ProviderToolSupport::openAiToolChoice($request->toolChoice);
        }
        $httpConfig = $this->config;
        if (isset($request->options['timeout'])) $httpConfig['timeout'] = max(1, (int) $request->options['timeout']);
        $client = $stream ? ProviderHttp::streaming($httpConfig) : ProviderHttp::make($httpConfig);
        try {
            return $client->withToken($key)->post(rtrim($this->config['base_url'], '/').'/responses', $payload);
        } catch (ConnectionException $e) {
            throw ProviderException::unreachable($this->provider, $e->getMessage());
        }
    }

    private function present(array $data, ChatRequest $request): ChatResponse
    {
        if (($data['status'] ?? '') === 'failed' || isset($data['error'])) {
            throw new ProviderException((string) data_get($data, 'error.message', 'Responses API failed.'), $this->provider);
        }
        $text = '';
        $calls = [];
        foreach ($data['output'] ?? [] as $item) {
            if (($item['type'] ?? '') === 'message') {
                foreach ($item['content'] ?? [] as $part) {
                    if (($part['type'] ?? '') === 'output_text') $text .= $part['text'] ?? '';
                }
            }
            if (($item['type'] ?? '') === 'function_call') {
                $calls[] = ToolCall::fromArray(['id' => $item['call_id'] ?? $item['id'] ?? '', 'name' => $item['name'], 'arguments' => $item['arguments'] ?? '{}']);
            }
        }

        return new ChatResponse(
            content: $text, modelId: '', provider: $this->provider,
            apiModel: $data['model'] ?? $request->apiModel,
            meta: ['usage' => $data['usage'] ?? null, 'id' => $data['id'] ?? null, 'finish_reason' => ($data['status'] ?? '') === 'incomplete' ? 'length' : 'stop'],
            toolCalls: $calls,
        );
    }
}
