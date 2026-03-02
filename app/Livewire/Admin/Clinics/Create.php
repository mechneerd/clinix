<?php

namespace App\Livewire\Admin\Clinics;

use App\Services\ClinicService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Create Clinic — Clinix')]
class Create extends Component
{
    use WithFileUploads;

    public string  $name               = '';
    public string  $description        = '';
    public string  $email              = '';
    public string  $phone              = '';
    public string  $alternate_phone    = '';
    public string  $website            = '';
    public string  $address            = '';
    public string  $city               = '';
    public string  $state              = '';
    public string  $country            = '';
    public string  $postal_code        = '';
    public string  $primary_color      = '#3B82F6';
    public string  $secondary_color    = '#10B981';
    public int     $appointment_duration = 30;
    public bool    $show_on_public_listing = true;
    public         $logo               = null;

    // Working hours
    public array $working_hours = [
        'monday'    => ['open' => true,  'start' => '09:00', 'end' => '18:00'],
        'tuesday'   => ['open' => true,  'start' => '09:00', 'end' => '18:00'],
        'wednesday' => ['open' => true,  'start' => '09:00', 'end' => '18:00'],
        'thursday'  => ['open' => true,  'start' => '09:00', 'end' => '18:00'],
        'friday'    => ['open' => true,  'start' => '09:00', 'end' => '18:00'],
        'saturday'  => ['open' => true,  'start' => '09:00', 'end' => '14:00'],
        'sunday'    => ['open' => false, 'start' => '09:00', 'end' => '18:00'],
    ];

    protected function rules(): array
    {
        return [
            'name'                => 'required|string|max:255',
            'email'               => 'nullable|email',
            'phone'               => 'required|string|max:20',
            'address'             => 'required|string',
            'city'                => 'required|string|max:100',
            'state'               => 'required|string|max:100',
            'country'             => 'required|string|max:100',
            'postal_code'         => 'nullable|string|max:20',
            'appointment_duration'=> 'required|integer|min:5|max:120',
            'logo'                => 'nullable|image|max:2048',
            'primary_color'       => 'nullable|string|max:10',
        ];
    }

    public function save(ClinicService $service): void
    {
        $this->validate();

        if (!$service->canCreateMoreClinics(auth()->user())) {
            $this->addError('name', 'You have reached the maximum number of clinics for your plan.');
            return;
        }

        $data = $this->only([
            'name','description','email','phone','alternate_phone','website',
            'address','city','state','country','postal_code',
            'primary_color','secondary_color','appointment_duration',
            'show_on_public_listing','working_hours','logo',
        ]);

        $clinic = $service->createClinic($data, auth()->user());

        $this->redirect(route('admin.clinics.show', $clinic->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.clinics.create');
    }
}
