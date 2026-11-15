<?php

namespace App\Lab\Data;

use Illuminate\Validation\ValidationException;

/**
 * Canonical, provider-neutral entity schema contract.
 *
 * This class only validates metadata. It never accepts a table name, SQL or
 * customer record payload, so it is safe to use before an owner Apply step.
 */
final class EntityDefinition
{
    public const TYPES = ['text', 'textarea', 'integer', 'decimal', 'boolean', 'date', 'datetime', 'select', 'reference'];
    public const MAX_ENTITIES_PER_PROJECT = 20;
    public const MAX_FIELDS_PER_ENTITY = 50;

    /** @return array<string, mixed> */
    public static function validate(array $definition, ?array $previous = null): array
    {
        $errors = [];
        $allowed = ['entity_uuid', 'label', 'version', 'fields'];
        self::keys($definition, $allowed, '', $errors);
        self::uuid($definition['entity_uuid'] ?? null, 'entity_uuid', $errors);
        self::text($definition['label'] ?? null, 1, 120, 'label', $errors);
        if (!isset($definition['version']) || !is_int($definition['version']) || $definition['version'] < 1) {
            $errors['version'] = 'Version must be a positive integer.';
        }
        if (!is_array($definition['fields'] ?? null) || array_is_list($definition['fields']) === true) {
            $errors['fields'] = 'Fields must be an object keyed by stable field UUID.';
        } elseif (count($definition['fields']) > self::MAX_FIELDS_PER_ENTITY) {
            $errors['fields'] = 'An entity may contain at most 50 fields.';
        } else {
            self::validateFields($definition['fields'], $errors, $previous['fields'] ?? []);
        }

        if ($previous !== null) {
            self::uuid($previous['entity_uuid'] ?? null, 'previous.entity_uuid', $errors);
            if (($previous['entity_uuid'] ?? null) !== ($definition['entity_uuid'] ?? null)) {
                $errors['entity_uuid'] = 'An entity change must keep the same entity UUID.';
            }
            if (isset($previous['version'], $definition['version']) && is_int($previous['version']) && is_int($definition['version'])
                && $definition['version'] !== $previous['version'] + 1) {
                $errors['version'] = 'Schema changes must increment the version by one.';
            }
            self::preserveArchivedFields($definition['fields'] ?? [], $previous['fields'] ?? [], $errors);
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
        return $definition;
    }

    /** @param array<string, mixed> $fields @param array<string, mixed> $errors */
    private static function validateFields(array $fields, array &$errors, array $previous): void
    {
        $labels = [];
        foreach ($fields as $fieldUuid => $field) {
            $path = "fields.{$fieldUuid}";
            if (!is_string($fieldUuid) || !self::isUuid($fieldUuid)) {
                $errors[$path] = 'Field keys must be stable UUIDs.';
                continue;
            }
            if (!is_array($field)) {
                $errors[$path] = 'Field definition must be an object.';
                continue;
            }
            self::keys($field, ['label', 'type', 'required', 'archived', 'config'], $path, $errors);
            self::text($field['label'] ?? null, 1, 120, "{$path}.label", $errors);
            $labelKey = mb_strtolower(trim((string)($field['label'] ?? '')));
            if ($labelKey !== '' && isset($labels[$labelKey])) {
                $errors["{$path}.label"] = 'Field labels must be unique within an entity.';
            }
            $labels[$labelKey] = true;
            if (!is_string($field['type'] ?? null) || !in_array($field['type'], self::TYPES, true)) {
                $errors["{$path}.type"] = 'Unsupported field type.';
            }
            foreach (['required', 'archived'] as $flag) if (!is_bool($field[$flag] ?? null)) {
                $errors["{$path}.{$flag}"] = 'Field flags must be boolean.';
            }
            self::validateConfig($field, $path, $errors);
            if (isset($previous[$fieldUuid]) && is_array($previous[$fieldUuid])) {
                $oldType = $previous[$fieldUuid]['type'] ?? null;
                if ($oldType !== null && ($field['type'] ?? null) !== $oldType) {
                    $errors["{$path}.type"] = 'Changing an existing field type is not additive.';
                }
                if (($previous[$fieldUuid]['archived'] ?? false) === true && ($field['archived'] ?? false) === false) {
                    $errors["{$path}.archived"] = 'Archived fields cannot be silently restored.';
                }
            }
        }
    }

    /** @param array<string, mixed> $field @param array<string, mixed> $errors */
    private static function validateConfig(array $field, string $path, array &$errors): void
    {
        $config = $field['config'] ?? [];
        if (!is_array($config)) {
            $errors["{$path}.config"] = 'Field config must be an object.';
            return;
        }
        $type = $field['type'] ?? null;
        if ($type === 'decimal') {
            if (!isset($config['precision'], $config['scale']) || !is_int($config['precision']) || !is_int($config['scale'])
                || $config['precision'] < 1 || $config['precision'] > 18 || $config['scale'] < 0 || $config['scale'] > 2 || $config['scale'] > $config['precision']) {
                $errors["{$path}.config"] = 'Decimal fields require precision 1–18 and scale 0–2.';
            }
            self::keys($config, ['precision', 'scale'], "{$path}.config", $errors);
        } elseif ($type === 'select') {
            $options = $config['options'] ?? null;
            if (!is_array($options) || count($options) < 1 || count($options) > 50 || array_filter($options, fn($option) => !is_string($option) || trim($option) === '') !== []) {
                $errors["{$path}.config.options"] = 'Select fields require 1–50 non-empty options.';
            }
            self::keys($config, ['options'], "{$path}.config", $errors);
        } elseif ($type === 'reference') {
            self::uuid($config['entity_uuid'] ?? null, "{$path}.config.entity_uuid", $errors);
            self::keys($config, ['entity_uuid'], "{$path}.config", $errors);
        } elseif ($config !== []) {
            $errors["{$path}.config"] = 'This field type does not accept config.';
        }
    }

    /** @param array<string, mixed> $fields @param array<string, mixed> $previous @param array<string, mixed> $errors */
    private static function preserveArchivedFields(array $fields, array $previous, array &$errors): void
    {
        foreach ($previous as $fieldUuid => $field) {
            if (!is_array($field) || ($field['archived'] ?? false) !== true) continue;
            if (!isset($fields[$fieldUuid]) || ($fields[$fieldUuid]['archived'] ?? false) !== true) {
                $errors["fields.{$fieldUuid}"] = 'Archived fields must remain present for historical answers.';
            }
        }
    }

    /** @param array<string, mixed> $value @param list<string> $allowed @param array<string, mixed> $errors */
    private static function keys(array $value, array $allowed, string $path, array &$errors): void
    {
        foreach (array_keys($value) as $key) if (!in_array($key, $allowed, true)) {
            $errors[$path === '' ? (string)$key : "{$path}.{$key}"] = 'Unsupported schema key.';
        }
    }

    /** @param array<string, mixed> $errors */
    private static function text(mixed $value, int $min, int $max, string $path, array &$errors): void
    {
        if (!is_string($value) || mb_strlen(trim($value)) < $min || mb_strlen($value) > $max || preg_match('/[\x00-\x1F\x7F]/', $value)) {
            $errors[$path] = "Use plain text of {$min}–{$max} characters.";
        }
    }

    /** @param array<string, mixed> $errors */
    private static function uuid(mixed $value, string $path, array &$errors): void
    {
        if (!is_string($value) || !self::isUuid($value)) $errors[$path] = 'Use a UUID.';
    }

    private static function isUuid(string $value): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value);
    }
}
