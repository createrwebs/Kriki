<?php

require __DIR__.'/private-slip-flow.php';

$proof = $fixture('legacy-attempt', ['module_uuid'=>$paymentsMod->uuid,'attempt_uuid'=>$attemptUuid]);
$as($owner);
$legacy = json_decode($ownerHttp($imagePath)->getContent(), true);
$legacyPath = substr($imagePath, 0, -strlen('/image')).'/legacy-image';
formCheck(($legacy['legacy'] ?? false) && $legacy['url'] === $legacyPath && now()->diffInSeconds(Illuminate\Support\Carbon::parse($legacy['expires_at'])) <= 300, 'Historical inline proof gets an explicit owner-session URL and bounded UI lifetime');
$response = $ownerHttp($legacyPath);
formCheck($response->getStatusCode() === 200 && $response->headers->get('Content-Type') === 'image/png' && str_starts_with($response->getContent(), "\x89PNG\r\n\x1a\n"), 'Actual legacy owner route serves normalized PNG bytes');
formCheck(str_contains($response->headers->get('Cache-Control'), 'no-store') && $response->headers->get('X-Content-Type-Options') === 'nosniff', 'Historical proof is private, non-cacheable and cannot execute as HTML');
formCheck($ownerHttp($legacyPath.'?token=forged')->getStatusCode() === 422, 'Legacy image accepts no query capability or arbitrary source');
$as(null); formCheck($ownerHttp($legacyPath)->getStatusCode() === 401, 'Copied historical URL confers no access without an owner session');
$as($other); formCheck($ownerHttp($legacyPath)->getStatusCode() === 403, 'Another account cannot download historical proof');
$as($owner);
$expect(fn () => $commerce->legacySlipImage($owner,$sibling,$paymentsMod,$attemptUuid), 404, 'Historical proof is scoped to the original project');
$project->forceFill(['frozen_at'=>now()])->save(); $allow('manual_payments',false);
formCheck($ownerHttp($legacyPath)->getStatusCode() === 200, 'Read-only historical proof remains available after freeze/downgrade');
$project->forceFill(['frozen_at'=>null])->save(); $allow('manual_payments');
$history = $commerce->listOrderAttempts($owner,$project,$ordersMod,$orderUuid)['items'];
$attempt = collect($history)->firstWhere('uuid',$attemptUuid);
formCheck($attempt['status'] === $proof['status'] && $attempt['version'] === $proof['version'] && $attempt['slip_sha256'] === $proof['sha256'] && !$attempt['image_available'], 'Viewing preserves accepted proof hash/review/version and marks historical transport');
formCheck(!str_contains(json_encode($history),'data:image') && !str_contains(json_encode($history),'legacy-image'), 'History lists contain no inline bytes or download URLs');
formCheck($commerce->publicGetOrder($catalogMod,$orderUuid,$accessToken)['order']['status'] === 'paid', 'Historical viewing cannot change paid status');
$expect(fn () => $commerce->legacySlipImage($owner,$project,$paymentsMod,$receipt['slip']['uuid']),404,'Storage-backed proof cannot use the historical image endpoint');
formResult('historical inline proof owner HTTP viewing without evidence migration or mutation');
