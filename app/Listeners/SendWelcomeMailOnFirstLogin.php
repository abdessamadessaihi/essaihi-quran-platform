<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeFirstLoginMail;
use Illuminate\Support\Facades\DB;

class SendWelcomeMailOnFirstLogin
{
  public function handle(Login $event)
{
    /** @var \App\Models\User $user */
    $user = $event->user;

    // 🌟 إذا كان المستخدم لديه google_id، نتخطى الـ Listener تمامًا لأننا سنعالجه في الـ Controller
    if ($user && !empty($user->google_id)) {
        return;
    }

    // الكود العادي للمستخدمين العاديين (تسجيل يدوي)
    if ($user && !$user->has_logged_in_before) {
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeFirstLoginMail($user));

        \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->update(['has_logged_in_before' => true]);

        $user->has_logged_in_before = true;
    }
}
}