<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentBookedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Appointment Confirmed — ' . $this->appointment->appointment_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been successfully booked.')
            ->line('**Appointment #:** ' . $this->appointment->appointment_number)
            ->line('**Date:** ' . $this->appointment->appointment_date->format('l, F j, Y'))
            ->line('**Time:** ' . $this->appointment->start_time)
            ->line('**Doctor:** Dr. ' . ($this->appointment->doctor->name ?? 'N/A'))
            ->line('**Clinic:** ' . ($this->appointment->clinic->name ?? 'N/A'))
            ->line('**Type:** ' . ucfirst(str_replace('_', ' ', $this->appointment->type)))
            ->action('View Appointment', route('patient.appointments'))
            ->line('Please arrive 10 minutes before your scheduled time.')
            ->salutation('— Clinix Health Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'                => 'appointment_booked',
            'title'               => 'Appointment Confirmed',
            'body'                => 'Your appointment ' . $this->appointment->appointment_number . ' on ' . $this->appointment->appointment_date->format('M d') . ' at ' . $this->appointment->start_time . ' has been confirmed.',
            'appointment_id'      => $this->appointment->id,
            'appointment_number'  => $this->appointment->appointment_number,
            'url'                 => route('patient.appointments'),
            'icon'                => 'calendar',
            'color'               => 'green',
        ];
    }
}