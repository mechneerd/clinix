<?php

namespace App\Notifications;

use App\Models\LabOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LabReportReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LabOrder $labOrder) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🧪 Lab Report Ready — ' . $this->labOrder->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your lab results are now available.')
            ->line('**Order #:** ' . $this->labOrder->order_number)
            ->line('**Lab:** ' . ($this->labOrder->lab->name ?? 'N/A'))
            ->line('**Completed:** ' . $this->labOrder->completed_at?->format('M d, Y H:i'))
            ->line('**Tests:** ' . $this->labOrder->items->count() . ' test(s)')
            ->action('View Lab Results', route('patient.lab-orders'))
            ->line('Please consult your doctor to understand the results.')
            ->salutation('— Clinix Health Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'lab_report_ready',
            'title'        => 'Lab Report Ready',
            'body'         => 'Your lab results for order ' . $this->labOrder->order_number . ' are now available.',
            'lab_order_id' => $this->labOrder->id,
            'order_number' => $this->labOrder->order_number,
            'url'          => route('patient.lab-orders'),
            'icon'         => 'beaker',
            'color'        => 'violet',
        ];
    }
}