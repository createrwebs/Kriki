<?php
require __DIR__.'/forms-bootstrap.php';

use App\Lab\Forms\BookingTemplate;
use App\Lab\Forms\FormDefinition;

$fixture = json_decode(file_get_contents(__DIR__.'/../fixtures/forms/booking-v1.json'), true, flags: JSON_THROW_ON_ERROR);
$rules = new FormDefinition;
formCheck(BookingTemplate::definition() === $fixture, 'Template matches shared fixture');
formCheck($rules->validate($fixture) === $fixture, 'Canonical template accepted');
$field = ['id' => 'field_11111111-1111-4111-8111-111111111111', 'label' => 'แพ้อาหาร', 'type' => 'text', 'required' => false, 'archived' => false];
$definition = $fixture;
$definition['fields'][] = $field;
formCheck(count($rules->validate($definition)['fields']) === 6, 'Thai custom field accepted');
$renamed = $definition;
$renamed['fields'][5]['label'] = 'ข้อมูลแพ้อาหาร';
formCheck($rules->validate($renamed, $definition)['fields'][5]['id'] === $field['id'], 'Rename preserves ID');
$archived = $definition;
$archived['fields'][5]['archived'] = true;
formCheck($rules->validate($archived, $definition)['fields'][5]['archived'], 'Archive preserves field');
$changed = $definition;
$changed['fields'][5]['type'] = 'number';
formInvalid(fn () => $rules->validate($changed, $definition), 'Existing type cannot change');
formInvalid(fn () => $rules->validate($fixture, $definition), 'Existing field cannot disappear');
$bad = $fixture;
$bad['fields'][0]['archived'] = true;
formInvalid(fn () => $rules->validate($bad), 'Core cannot be archived');
$bad = $definition;
$bad['fields'][] = $field;
formInvalid(fn () => $rules->validate($bad), 'Duplicate IDs rejected');
$bad = $definition;
$bad['fields'][5]['label'] = '<script>alert(1)</script>';
formInvalid(fn () => $rules->validate($bad), 'Markup rejected');
$bad = $fixture;
for ($index = 1; $index <= 21; $index++) {
    $bad['fields'][] = array_replace($field, ['id' => 'field_'.sprintf('%08d', $index).'-1111-4111-8111-111111111111']);
}
formInvalid(fn () => $rules->validate($bad), '21 active custom fields rejected');
$bad = $definition;
$bad['fields'][5]['type'] = 'select';
$bad['fields'][5]['options'] = array_map(fn ($i) => ['id' => 'option_'.$i, 'label' => 'ตัวเลือก '.$i], range(1, 31));
formInvalid(fn () => $rules->validate($bad), '31 options rejected');
$bad = $definition;
$bad['fields'][5]['sql'] = 'select 1';
formInvalid(fn () => $rules->validate($bad), 'Unknown field properties rejected');
$sameLabel = $definition;
$sameLabel['fields'][] = array_replace($field, ['id' => 'field_22222222-2222-4222-8222-222222222222']);
formCheck(count($rules->validate($sameLabel)['fields']) === 7, 'Duplicate labels preserve distinct field IDs');
formResult('form definition');
