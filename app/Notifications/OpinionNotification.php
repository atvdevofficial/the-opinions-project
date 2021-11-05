<?php

namespace App\Notifications;

use App\Models\Opinion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class OpinionNotification extends Notification
{
    use Queueable;

    protected Opinion $opinion;
    protected $subtitle;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Opinion $opinion, string $subtitle)
    {
        $this->opinion = $opinion;
        $this->subtitle = $subtitle;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Opinion Notification',
            'subtitle' => $this->subtitle,
            'data' => [
                'opinion' => Arr::only($this->opinion->toArray(), ['id', 'text', 'topics', 'created_at']),
            ],
        ];
    }
}
