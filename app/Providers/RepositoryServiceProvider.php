<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    ClinicRepositoryInterface,
    PatientRepositoryInterface,
    AppointmentRepositoryInterface,
    StaffRepositoryInterface,
    PackageRepositoryInterface
};
use App\Repositories\Eloquent\{
    ClinicRepository,
    PatientRepository,
    AppointmentRepository,
    StaffRepository,
    PackageRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All repository bindings
     */
    protected array $repositories = [
        ClinicRepositoryInterface::class => ClinicRepository::class,
        PatientRepositoryInterface::class => PatientRepository::class,
        AppointmentRepositoryInterface::class => AppointmentRepository::class,
        StaffRepositoryInterface::class => StaffRepository::class,
        PackageRepositoryInterface::class => PackageRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    public function boot(): void
    {
        //
    }
}