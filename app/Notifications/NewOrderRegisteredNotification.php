<?php

namespace App\Notifications;

use App\Models\Market\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderRegisteredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $formattedAmount = rtrim(rtrim(number_format($this->order->order_final_amount, 2), '0'), '.');
        return [
            'event' => 'new_order', // نوع رویداد
            'message' => "New order <b>#{$this->order->id}</b> received",
            'url'   => route('admin.market.order.show', $this->order->id),
            'meta'  => [
                'order_id' => $this->order->id,
                'customer_name' => $this->order->user->full_name,
            ],
        ];
    }
}
