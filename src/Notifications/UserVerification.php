<?php

namespace NovinVision\MultiStepLogin\Notifications;

use App\Channels\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SimpleMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class UserVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected \NovinVision\MultiStepLogin\Models\UserVerification $userVerification)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return match ($this->userVerification->field) {
            'mobile' => ['sms'],
            default => ['mail']
        };
    }

    public function toSms(object $notifiable): SimpleMessage
    {
        return (new SimpleMessage)
            ->line(sprintf("%s کد تایید:", $this->userVerification->code))
            ->line(sprintf("%s #%s", parse_url(config('app.url'), PHP_URL_HOST), $this->userVerification->code));
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('novinvision.multi-step-login::multi-step-login.verify-notification-subject'))
            ->line(trans('novinvision.multi-step-login::multi-step-login.verify-notification-title', ['name' => $notifiable->name]))
            ->line(trans('novinvision.multi-step-login::multi-step-login.verify-notification-description', [
                'field' => trans("novinvision.multi-step-login::multi-step-login.{$this->userVerification->field}"),
                'app_name' => config('app.name'),
            ]))
            ->line(new HtmlString('<h2 style="margin: 15px 0; text-align: center;letter-spacing: 10px;font-weight: 900">' . $this->userVerification->code . '</h2>'))
            ->action(trans("novinvision.multi-step-login::multi-step-login.verify-action-text"), route('verify', ['code', $this->userVerification->code]));
    }
}
