<?php

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrescriptionCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Prescription $prescription) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('💊 New Prescription — ' . $this->prescription->prescription_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new prescription has been issued for you.')
            ->line('**Prescription #:** ' . $this->prescription->prescription_number)
            ->line('**Doctor:** Dr. ' . ($this->prescription->doctor->name ?? 'N/A'))
            ->line('**Medications:** ' . $this->prescription->items->count() . ' item(s)')
            ->when($this->prescription->follow_up_date, fn($m) =>
                $m->line('**Follow-up:** ' . $this->prescription->follow_up_date->format('M d, Y'))
            )
            ->action('View Prescription', route('patient.prescriptions'))
            ->line('Follow the dosage instructions carefully.')
            ->salutation('— Clinix Health Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'                => 'prescription_created',
            'title'               => 'New Prescription',
            'body'                => 'Dr. ' . ($this->prescription->doctor->name ?? '') . ' has issued a new prescription (' . $this->prescription->prescription_number . ').',
            'prescription_id'     => $this->prescription->id,
            'prescription_number' => $this->prescription->prescription_number,
            'url'                 => route('patient.prescriptions'),
            'icon'                => 'document-text',
            'color'               => 'emerald',
        ];
    }
}