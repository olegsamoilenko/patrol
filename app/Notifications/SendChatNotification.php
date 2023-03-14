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
    public function __construct($message, $title, $url, $fcmTokens)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;

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
            ->withClickAction($this->url)
            ->withPriority('high')->asMessage($this->fcmTokens);
    }
}
