<?php
require __DIR__.'/form-owner-api.php';

use App\Lab\Forms\FormProposal;

$project->forceFill(['frozen_at' => null])->save();
$proposals = app(FormProposal::class);
$proposal = $proposals->propose($owner, $project, $form, 2, [['op' => 'add', 'field' => ['label' => 'แพ้อาหาร', 'type' => 'text', 'required' => false]]]);
formCheck(count($proposal['definition']['fields']) === 6, 'AI proposal adds optional Thai field');
formCheck($proposal['base_version'] === 2 && $form->versions()->count() === 2, 'Proposal does not persist version');
formCheck($form->fresh()->active_version === null, 'Proposal does not activate form');
formInvalid(fn () => $proposals->propose($owner, $project, $form, 2, [['op' => 'archive', 'id' => 'phone']]), 'Proposal cannot archive core');
formInvalid(fn () => $proposals->propose($owner, $project, $form, 2, [['op' => 'sql', 'sql' => 'SELECT 1']]), 'Proposal cannot apply SQL');
formInvalid(fn () => $proposals->propose($owner, $project, $form, 2, [['op' => 'add', 'field' => ['label' => '<script>x</script>', 'type' => 'text', 'required' => false]]]), 'Proposal cannot inject markup');
try { $proposals->propose($owner, $project, $form, 1, []); formCheck(false, 'Stale proposal denied'); }
catch (Symfony\Component\HttpKernel\Exception\HttpException $error) { formCheck($error->getStatusCode() === 409, 'Stale proposal returns 409'); }
formResult('AI form proposals');
