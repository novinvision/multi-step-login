<?php

namespace NovinVision\MultiStepLogin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SimpleMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class ResetPassword extends Notification
{
    use Queueable;

    protected string $signedRoute = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected $token)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'mail',
            ...($notifiable->routeNotificationFor('sms') ? ['sms'] : [])
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('novinvision.multi-step-login::multi-step-login.forget_password_title', ['name' => $notifiable->name]))
            ->line(__('novinvision.multi-step-login::multi-step-login.dear_user', ['name' => $notifiable->name]))
            ->line(__('novinvision.multi-step-login::multi-step-login.forget_password_sent_description', ['site_name' => config('app.name')]))
            ->action(__('novinvision.multi-step-login::multi-step-login.click_to_forget_password'), $this->getSignedRoute($notifiable))
            ->line(__('novinvision.multi-step-login::multi-step-login.forget_password_abort_description', ['minutes' => config('auth.passwords.users.expire', 30)]));
    }

    /**
     * @param object $notifiable
     * @return SimpleMessage
     */
    public function toSms(object $notifiable)
    {
        return (new SimpleMessage())
            ->line(__('novinvision.multi-step-login::multi-step-login.dear_user', ['name' => $notifiable->name]))
            ->action(__('novinvision.multi-step-login::multi-step-login.click_to_forget_password'), $this->getSignedRoute($notifiable));
    }

    public function getSignedRoute($notifiable): string
    {
        if (!$this->signedRoute) {
            $this->signedRoute = URL::signedRoute('forget-password-change-password',
                [
                    'hash' => Crypt::encrypt([
                        'user_type' => get_class($notifiable),
                        'user_id' => $notifiable->getKey(),
                        'token' => $this->token
                    ]),
                ]
            );
        }

        return $this->signedRoute;
    }
}
