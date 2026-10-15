<?php
require __DIR__.'/form-owner-api.php';

$files = app(App\Lab\Forms\FormEmbed::class)->files($form);
formCheck(count($files) === 3, 'Embed has wrapper/client/config');
formCheck(! isset($files['src/App.jsx']) && ! isset($files['src/index.css']), 'Embed preserves existing app and styling');
$config = json_decode($files['src/krikkit-forms/config.json'], true);
formCheck($config['formUuid'] === $form->uuid, 'Embed bound to selected form');
formCheck(! str_contains(json_encode($files), 'fixture-private'), 'Embed contains no private key');
formCheck(str_contains($files['src/krikkit-forms/BookingForm.jsx'], "from './client.js'"), 'Embed imports shipped client');
formResult('form embed');
