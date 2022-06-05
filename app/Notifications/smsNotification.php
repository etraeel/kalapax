<?php

namespace App\Notifications;

use App\Notifications\channels\ghasedakChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class smsNotification extends Notification
{
    use Queueable;


    public $receptor;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($receptor , $message)
    {
        $this->message = $message;
        $this->receptor = $receptor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ghasedakChannel::class];
    }

}
