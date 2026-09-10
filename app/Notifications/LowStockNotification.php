<?php

namespace App\Notifications;

use App\Models\Market\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    private array $lowStockItems;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
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
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // تعداد محصولات کم موجودی
        $count = count($this->lowStockItems);
        $url = $count === 1 ? route('admin.market.warehouse.variant.edit',['warehouse' => $this->lowStockItems[0]['warehouse_id'], 'warehouseVariant' => $this->lowStockItems[0]['product_variant_id']])
        : route('admin.market.warehouse.index');

        $message = $count === 1
            ? 'Low inventory: ' . '<b>' . $this->lowStockItems[0]['color'] . ' ' . $this->lowStockItems[0]['name'] . ' — ' . $this->lowStockItems[0]['size'] . '</b>'
            : "Low inventory for <b>{$count}</b> variants";

        return [
            'event' => 'low_stock',
            'message' => $message,
            'url'   => $url,
            'meta' => [
                'count' => $count,
                'items' => $this->lowStockItems,
            ],
        ];
    }
}
