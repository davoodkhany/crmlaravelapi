<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Contracts\Queue\ShouldQueue;
class NotificationForgetPassword extends Notification implements  ShouldQueue
{
    use Queueable;

    public $token;
    public $email;
    /**
     * Create a new notification instance.
     */
    public function __construct($token,$email)
    {
        $this->token = $token;
        $this->email = $email;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = ('http://localhost:3000/auth/reset-password'.'?token='.$this->token .'&email='.$this->email);
        return (new MailMessage)
        ->subject(Lang::get('اعلان بازیابی  رمز عبور'))
        ->line(Lang::get('شما این ایمیل را دریافت می کنید زیرا ما درخواست بازنشانی رمز عبور را برای حساب کاربری شما دریافت کرده ایم.'))
        ->action(Lang::get('تغییر رمز عبور'), $url)
        ->line(Lang::get('این پیوند بازنشانی رمز عبور در :count دقیقه منقضی می شود.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
        ->line(Lang::get('اگر درخواست بازنشانی رمز عبور را ارسال نکرده اید، نیازی به انجام کار دیگری نیست.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
