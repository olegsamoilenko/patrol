<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kutia\Larafirebase\Messages\FirebaseMessage;

class SendChatNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $url;
    protected $fcmTokens;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $title, $fcmTokens)
    {
        $this->title = $title;
        $this->message = $message;

        $this->fcmTokens = $fcmTokens;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        return (new FirebaseMessage)
            ->withTitle($this->title)
            ->withBody($this->message)
            ->withClickAction('https://dftg.uareg.com')
            ->withPriority('high')->asMessage($this->fcmTokens);
    }
}
