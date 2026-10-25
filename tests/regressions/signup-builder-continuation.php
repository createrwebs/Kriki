<?php
require __DIR__.'/signup-business-profile.php';

signupGuestSession();
$entry = route('lab.builder');
[$guestEntry] = signupHttp($entry,'GET',[],'192.0.2.94',true,'text/html');
formCheck($guestEntry->getStatusCode() === 302 && app('session')->driver()->get('url.intended') === $entry, 'Anonymous builder entry preserves intended route through login/register');
$payload = ['name'=>'Fictional builder signup','email'=>'builder-signup@example.invalid','password'=>'Fixture-password-123','password_confirmation'=>'Fixture-password-123','company_name'=>'Fictional cafe'];
[$created] = signupHttp('/register','POST',$payload,'192.0.2.94',true,'text/html');
formCheck($created->getStatusCode() === 302 && $created->headers->get('Location') === $entry, 'New signup resumes the original builder rather than losing its entry route');
$newOwner = App\Models\User::where('email',$payload['email'])->firstOrFail();
[$unverified] = signupHttp($entry,'GET',[],'192.0.2.94',true,'text/html');
formCheck($unverified->getStatusCode() === 302 && !$newOwner->hasVerifiedEmail() && app('session')->driver()->get('url.intended') === $entry, 'Verification remains required and retains builder continuation');
$mail = Illuminate\Support\Facades\Notification::sent($newOwner,App\Notifications\ConfirmEmailAddress::class)->first()->toMail($newOwner);
[$verified] = signupHttp($mail->actionUrl,'GET',[],'192.0.2.94',true,'text/html');
formCheck($verified->getStatusCode() === 302 && $verified->headers->get('Location') === $entry && $newOwner->fresh()->hasVerifiedEmail(), 'Verified signup returns to the intended builder');
signupGuestSession(); app('session')->driver()->put('url.intended',$entry);
[$login] = signupHttp('/login','POST',['email'=>$payload['email'],'password'=>$payload['password']],'192.0.2.95',true,'text/html');
formCheck($login->getStatusCode() === 302 && $login->headers->get('Location') === $entry && Illuminate\Support\Facades\Auth::id() === $newOwner->id, 'Existing verified account also resumes builder after normal login');
formResult('new and existing account builder continuation through real authentication/verification HTTP');
