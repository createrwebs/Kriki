<?php

require __DIR__.'/forms-bootstrap.php';

use App\Lab\Data\EntityDefinition;
use Illuminate\Validation\ValidationException;

$fixture = json_decode(file_get_contents(__DIR__.'/../fixtures/data/entity-v1.json'), true, 512, JSON_THROW_ON_ERROR);
formCheck(EntityDefinition::validate($fixture) === $fixture, 'Canonical entity fixture validates');

$renamed = $fixture;
$renamed['version'] = 2;
$fieldId = array_key_first($renamed['fields']);
$renamed['fields'][$fieldId]['label'] = 'ทะเบียนรถ';
formCheck(EntityDefinition::validate($renamed, $fixture) === $renamed, 'Renaming preserves the stable field UUID');

$archived = $renamed;
$archived['version'] = 3;
$archived['fields'][$fieldId]['archived'] = true;
formCheck(EntityDefinition::validate($archived, $renamed) === $archived, 'Archiving keeps the historical field definition');

$invalid = $fixture;
$invalid['fields']['10000000-0000-4000-8000-000000000005'] = ['label' => 'ประเภท', 'type' => 'text', 'required' => false, 'archived' => false, 'config' => []];
try { EntityDefinition::validate($invalid); formCheck(false, 'Duplicate labels are rejected'); } catch (ValidationException) { formCheck(true, 'Duplicate labels are rejected'); }

$invalid = $fixture;
$invalid['fields'][$fieldId]['type'] = 'integer';
try { EntityDefinition::validate($invalid, $fixture); formCheck(false, 'Existing field type changes are rejected'); } catch (ValidationException) { formCheck(true, 'Existing field type changes are rejected'); }

$invalid = $fixture;
$invalid['fields'][$fieldId]['archived'] = true;
$invalid['version'] = 2;
$removed = $invalid;
unset($removed['fields'][$fieldId]);
try { EntityDefinition::validate($removed, $invalid); formCheck(false, 'Archived fields cannot disappear'); } catch (ValidationException) { formCheck(true, 'Archived fields cannot disappear'); }

$invalid = $fixture;
$invalid['fields'][$fieldId]['type'] = 'decimal';
$invalid['fields'][$fieldId]['config'] = ['precision' => 19, 'scale' => 3];
try { EntityDefinition::validate($invalid); formCheck(false, 'Decimal bounds are enforced'); } catch (ValidationException) { formCheck(true, 'Decimal bounds are enforced'); }

$invalid = $fixture;
for ($i = 0; $i < 51; $i++) {
    $id = sprintf('20000000-0000-4000-8000-%012d', $i + 1);
    $invalid['fields'][$id] = ['label' => 'field '.$i, 'type' => 'text', 'required' => false, 'archived' => false, 'config' => []];
}
try { EntityDefinition::validate($invalid); formCheck(false, 'Field count is bounded'); } catch (ValidationException) { formCheck(true, 'Field count is bounded'); }

formResult('entity definition contract');
