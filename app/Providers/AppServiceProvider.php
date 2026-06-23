<?php

namespace App\Providers;

use App\Services\HeaderNotificationService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer(['layouts.admin', 'layouts.app'], function ($view) {
            $view->with('headerNotifications', Auth::check()
                ? app(HeaderNotificationService::class)->forUser(Auth::user())
                : ['count' => 0, 'items' => []]
            );
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], true);
            $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Reset Sandi Akun Mondial Bakery')
                ->greeting('Halo ' . ($notifiable->name ?? 'Pelanggan') . ',')
                ->line('Kami menerima permintaan reset sandi untuk akun Mondial Bakery Anda.')
                ->action('Reset Sandi', $resetUrl)
                ->line('Link ini berlaku selama ' . $expireMinutes . ' menit.')
                ->line('Jika tombol reset tidak bisa dibuka, salin link berikut ke browser Anda:')
                ->line($resetUrl)
                ->line('Abaikan email ini jika Anda tidak meminta reset sandi.');
        });
    }
}
