<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword; 
use App\Notifications\CustomResetPasswordNotification; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    // Notification Helpers
    if (!function_exists('notifIcon')) {
        function notifIcon(string $type): string {
            return match(true) {
                str_contains($type,'Ward')      => '🌙',
                str_contains($type,'Revision')  => '🧠',
                str_contains($type,'Khatma')    => '📚',
                str_contains($type,'Article')   => '✍️',
                str_contains($type,'Streak')    => '🔥',
                str_contains($type,'Badge')     => '🏅',
                str_contains($type,'Family')    => '👨‍👩‍👧',
                str_contains($type,'Member')    => '👤',
                str_contains($type,'Admin')     => '📢',
                default                         => '🔔',
            };
        }

    }

    if (!function_exists('notifIconClass')) {
        function notifIconClass(string $type): string {
            return match(true) {
                str_contains($type,'Ward')      => 'ward-type',
                str_contains($type,'Revision')  => 'memo-type',
                str_contains($type,'Khatma')    => 'ward-type',
                str_contains($type,'Streak')    => 'streak-type',
                str_contains($type,'Badge')     => 'badge-type',
                str_contains($type,'Family')    => 'family-type',
                str_contains($type,'Admin')     => 'badge-type',
                default                         => 'default-type',
            };
        }
    }

    if (!function_exists('notifTitle')) {
        function notifTitle(string $type, array $data): string {
            return $data['title'] ?? 'إشعار جديد';
        }
    }

    if (!function_exists('notifDesc')) {
        function notifDesc(string $type, array $data): string {
            return $data['message'] ?? '';
        }
    }

    if (!function_exists('notifChannel')) {
        function notifChannel(string $channel): string {
            return match($channel) {
                'database' => 'داخلي',
                'email'    => 'بريد',
                'push'     => 'إشعار',
                default    => $channel,
            };
        }
    }
    if (file_exists(app_path('Helpers/NotificationHelpers.php'))) {
            require_once app_path('Helpers/NotificationHelpers.php');
        }
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new CustomResetPasswordNotification($token))->toMail($notifiable);
        });
}
}