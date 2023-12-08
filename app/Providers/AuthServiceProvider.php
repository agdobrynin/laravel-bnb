<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return env('VITE_API_ENDPOINT').'/reset-password?token='.$token.'&email='.$user->email;
        });

        VerifyEmail::toMailUsing(function (MustVerifyEmail $notifiable, string $url) {
            $urlPattern = env('VITE_API_ENDPOINT').'/verify-email/%s/%s/%s/%s';
            parse_str(parse_url($url)['query'] ?? '', $queryString);
            // TODO check vars in $queryString
            $verifyUrl = sprintf(
                $urlPattern,
                $notifiable->getKey(),
                sha1($notifiable->getEmailForVerification()),
                $queryString['expires'],
                $queryString['signature']
            );

            $expireDate = date('Y-m-d H:m:i', $queryString['expires']);

            return (new MailMessage)->subject(Lang::get('Verify Email Address'))
                ->greeting('Hello '.$notifiable->name)
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->lineIf(! empty($expireDate), 'Verification link available until '.$expireDate.'.')
                ->action(Lang::get('Verify Email Address'), $verifyUrl)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        });
    }
}
