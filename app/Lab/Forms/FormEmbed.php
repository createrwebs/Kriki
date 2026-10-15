<?php

namespace App\Lab\Forms;

use App\Models\LabForm;

final class FormEmbed
{
    public function files(LabForm $form): array
    {
        $url = parse_url(config('app.url'));
        abort_unless(isset($url['scheme'], $url['host']) && in_array($url['scheme'], ['http', 'https'], true), 503, 'Configure the application URL first.');
        $origin = $url['scheme'].'://'.$url['host'].(isset($url['port']) ? ':'.$url['port'] : '');
        return [
            'src/krikkit-forms/BookingForm.jsx' => file_get_contents(resource_path('js/lab/form-runtime/BookingForm.jsx')),
            'src/krikkit-forms/client.js' => file_get_contents(resource_path('js/lab/form-runtime/client.js')),
            'src/krikkit-forms/config.json' => json_encode(['apiOrigin' => $origin, 'formUuid' => $form->uuid], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n",
        ];
    }
}
