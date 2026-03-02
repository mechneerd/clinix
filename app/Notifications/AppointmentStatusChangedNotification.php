<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $oldStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->appointment->status));
        $emoji = match($this->appointment->status) {
            'confirmed'   => '✅',
            'cancelled'   => '❌',
            'completed'   => '🎉',
            'rescheduled' => '📅',
            default       => 'ℹ️',
        };

        $mail = (new MailMessage)
            ->subject("{$emoji} Appointment {$statusLabel} — {$this->appointment->appointment_number}")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Your appointment status has been updated to **{$statusLabel}**.")
            ->line('**Appointment #:** ' . $this->appointment->appointment_number)
            ->line('**Date:** ' . $this->appointment->appointment_date->format('l, F j, Y'))
            ->line('**Doctor:** Dr. ' . ($this->appointment->doctor->name ?? 'N/A'));

        if ($this->appointment->status === 'cancelled' && $this->appointment->cancellation_reason) {
            $mail->line('**Reason:** ' . $this->appointment->cancellation_reason);
        }

        return $mail
            ->action('View Details', route('patient.appointments'))
            ->salutation('— Clinix Health Team');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->appointment->status));
        return [
            'type'               => 'appointment_status_changed',
            'title'              => 'Appointment ' . $statusLabel,
            'body'               => 'Appointment ' . $this->appointment->appointment_number . ' is now ' . $statusLabel,
            'appointment_id'     => $this->appointment->id,
            'appointment_number' => $this->appointment->appointment_number,
            'old_status'         => $this->oldStatus,
            'new_status'         => $this->appointment->status,
            'url'                => route('patient.appointments'),
            'icon'               => 'calendar',
            'color'              => $this->appointment->status === 'cancelled' ? 'red' : 'blue',
        ];
    }
}