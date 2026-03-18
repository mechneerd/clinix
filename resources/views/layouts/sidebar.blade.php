<aside 
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen,
        'w-72': !sidebarCollapsed,
        'w-20': sidebarCollapsed
    }" 
    class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-all duration-300 lg:translate-x-0 lg:static lg:inset-0 lg:block overflow-hidden flex flex-col"
>
    <!-- Brand Logo Area -->
    <div class="h-20 flex items-center border-b border-slate-200 dark:border-slate-800 transition-all duration-300 shrink-0" 
         :class="sidebarCollapsed ? 'justify-center px-0' : 'px-6'">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand-teal flex animate-in fade-in zoom-in duration-300 items-center justify-center shadow-lg shadow-brand-teal/20 shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="overflow-hidden whitespace-nowrap">
                <span class="text-xl font-bold text-slate-900 dark:text-white">Clinix</span>
                @auth
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-brand-green">{{ str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'User') }}</p>
                @endauth
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-400 hover:text-slate-600 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navigation Area -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar overflow-x-hidden">
        @auth
            @php
            $user = auth()->user();
            $role = $user->getRoleNames()->first();
            
            $menuItems = match($role) {
                'super-admin' => [
                    ['route' => 'super-admin.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'super-admin.packages', 'icon' => 'package', 'label' => 'Packages'],
                    ['route' => 'super-admin.clinics', 'icon' => 'building', 'label' => 'Clinics'],
                    ['route' => 'super-admin.modules', 'icon' => 'box', 'label' => 'Modules'],
                    ['route' => 'super-admin.subscriptions', 'icon' => 'credit-card', 'label' => 'Subscriptions'],
                    ['route' => 'super-admin.analytics', 'icon' => 'chart', 'label' => 'Analytics'],
                    ['route' => 'super-admin.settings.locations', 'icon' => 'map-pin', 'label' => 'Global Locations'],
                    ['route' => 'super-admin.settings', 'icon' => 'settings', 'label' => 'Settings'],
                ],
                'clinic-admin' => [
                    ['route' => 'clinic.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'clinic.calendar', 'icon' => 'calendar', 'label' => 'Central Schedule'],
                    ['route' => 'clinic.subscription', 'icon' => 'credit-card', 'label' => 'Subscription'],
                    ['route' => 'clinic.clinic-setup', 'icon' => 'building', 'label' => 'Clinic Setup'],
                    ['route' => 'clinic.departments', 'icon' => 'folder', 'label' => 'Departments', 'module' => 'departments'],
                    ['route' => 'clinic.doctors', 'icon' => 'user-plus', 'label' => 'Doctors', 'module' => 'staff'],
                    ['route' => 'clinic.staff', 'icon' => 'users', 'label' => 'Staff Members', 'module' => 'staff'],
                    ['route' => 'clinic.patients', 'icon' => 'patient', 'label' => 'Patients', 'module' => 'patients'],
                    ['route' => 'clinic.appointments', 'icon' => 'calendar', 'label' => 'Appointments', 'module' => 'appointments'],
                    ['route' => 'clinic.medicines', 'icon' => 'pill', 'label' => 'Medicine Inventory', 'module' => 'medicines'],
                    ['route' => 'clinic.inventory-audit', 'icon' => 'clipboard', 'label' => 'Inventory Audit', 'module' => 'medicines'],
                    ['route' => 'clinic.lab-tests', 'icon' => 'flask', 'label' => 'Lab Tests', 'module' => 'laboratory'],
                    ['route' => 'clinic.lab-consumables', 'icon' => 'box', 'label' => 'Lab Consumables', 'module' => 'laboratory'],
                    ['route' => 'clinic.admissions', 'icon' => 'building', 'label' => 'Ward & Admissions', 'module' => 'inpatient'],
                    ['route' => 'clinic.billing', 'icon' => 'dollar', 'label' => 'Billing & Invoices', 'module' => 'billing'],
                    ['route' => 'clinic.workforce', 'icon' => 'users', 'label' => 'Workforce (HR)', 'module' => 'hr'],
                    ['route' => 'clinic.ledger', 'icon' => 'chart', 'label' => 'Global Ledger', 'module' => 'finance'],
                    ['route' => 'clinic.compliance', 'icon' => 'clipboard', 'label' => 'Compliance', 'module' => 'compliance'],
                    ['route' => 'clinic.reports', 'icon' => 'chart', 'label' => 'Reports', 'module' => 'reports'],
                    ['route' => 'clinic.settings.locations', 'icon' => 'map-pin', 'label' => 'Locations'],
                    ['route' => 'clinic.settings', 'icon' => 'settings', 'label' => 'Settings'],
                    ['route' => 'clinic.clinic-configuration', 'icon' => 'settings', 'label' => 'System Config'],
                ],
                'doctor' => [
                    ['route' => 'doctor.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'doctor.calendar', 'icon' => 'calendar', 'label' => 'Schedule Analytics'],
                    ['route' => 'doctor.appointments', 'icon' => 'calendar', 'label' => 'Appointments'],
                    ['route' => 'doctor.patients', 'icon' => 'patient', 'label' => 'Patients'],
                    ['route' => 'doctor.prescriptions', 'icon' => 'document', 'label' => 'Prescriptions'],
                    ['route' => 'doctor.lab-requests', 'icon' => 'inbox', 'label' => 'Lab Requests'],
                    ['route' => 'doctor.medical-records', 'icon' => 'clipboard', 'label' => 'Medical Records'],
                ],
                'nurse' => [
                    ['route' => 'nurse.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'nurse.appointments', 'icon' => 'calendar', 'label' => 'Appointments'],
                    ['route' => 'nurse.patients', 'icon' => 'patient', 'label' => 'Patients'],
                    ['route' => 'nurse.vitals', 'icon' => 'heart', 'label' => 'Vitals'],
                ],
                'lab_manager', 'lab_worker' => [
                    ['route' => 'lab.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'lab.tests', 'icon' => 'flask', 'label' => 'Tests'],
                    ['route' => 'lab.orders', 'icon' => 'inbox', 'label' => 'Orders'],
                    ['route' => 'lab.results', 'icon' => 'document', 'label' => 'Results'],
                ],
                'pharmacy_manager', 'pharmacy_worker' => [
                    ['route' => 'pharmacy.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'pharmacy.medicines', 'icon' => 'pill', 'label' => 'Medicines'],
                    ['route' => 'pharmacy.prescriptions', 'icon' => 'document', 'label' => 'Prescriptions'],
                ],
                'reception_manager', 'receptionist' => [
                    ['route' => 'reception.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'reception.appointments', 'icon' => 'calendar', 'label' => 'Appointments'],
                    ['route' => 'reception.patients', 'icon' => 'patient', 'label' => 'Patients'],
                    ['route' => 'reception.billing', 'icon' => 'dollar', 'label' => 'Invoices'],
                ],
                'patient' => [
                    ['route' => 'patient.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
                    ['route' => 'patient.calendar', 'icon' => 'calendar', 'label' => 'My Calendar'],
                    ['route' => 'patient.browse-clinics', 'icon' => 'building', 'label' => 'Browse Clinics'],
                    ['route' => 'patient.appointments', 'icon' => 'calendar', 'label' => 'My Appointments'],
                    ['route' => 'patient.prescriptions', 'icon' => 'document', 'label' => 'Prescriptions'],
                    ['route' => 'patient.lab-reports', 'icon' => 'flask', 'label' => 'Lab Reports'],
                    ['route' => 'patient.billing', 'icon' => 'dollar', 'label' => 'Invoices'],
                ],
                default => [['route' => 'dashboard', 'icon' => 'home', 'label' => 'Dashboard']],
            };
            @endphp

            @php
            $hasPackage = $user->clinic && $user->clinic->package_id && $user->clinic->package_expires_at && $user->clinic->package_expires_at->isFuture();
            @endphp

            @foreach($menuItems as $item)
                @php
                $isSettings = str_contains($item['route'], 'settings') || str_contains($item['label'], 'Settings');
                $isCoreRoute = in_array($item['route'], ['clinic.dashboard', 'clinic.subscription', 'clinic.clinic-setup']);
                
                $moduleEnabled = true;
                if ($role === 'clinic-admin' && isset($item['module']) && $user->clinic) {
                    $moduleEnabled = $user->clinic->isModuleEnabled($item['module']);
                }

                $showLink = ($role !== 'clinic-admin') || 
                            ($isCoreRoute || $isSettings || ($hasPackage && $moduleEnabled));

                $isActive = request()->routeIs($item['route']);
                @endphp

                @if($showLink)
                <a href="{{ route($item['route']) }}" 
                   wire:navigate.hover
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group relative {{ $isActive ? 'bg-brand-teal text-white shadow-md shadow-brand-teal/20' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 hover:text-brand-teal' }}"
                   :class="sidebarCollapsed ? 'justify-center' : ''"
                   title="{{ $item['label'] }}">
                    
                    <x-icons :name="$item['icon']" class="w-5 h-5 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-brand-teal' }}" />
                    
                    <span x-show="!sidebarCollapsed" 
                          x-transition:enter="transition ease-out duration-200"
                          x-transition:enter-start="opacity-0 -translate-x-2"
                          x-transition:enter-end="opacity-100 translate-x-0"
                          class="font-medium whitespace-nowrap">{{ $item['label'] }}</span>
                    
                    @if($item['route'] === 'reception.queue' && !sidebarCollapsed)
                        @php
                        $queueCount = \App\Models\Appointment::whereDate('appointment_date', today())
                            ->where('status', 'checked_in')
                            ->where('clinic_id', auth()->user()->staff?->clinic_id)
                            ->count();
                        @endphp
                        @if($queueCount > 0)
                        <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $queueCount }}</span>
                        @endif
                    @endif
                </a>
                @endif
            @endforeach
        @endauth
    </nav>

    <!-- Footer Area (User Profile / Toggle) -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-2 shrink-0 bg-slate-50/50 dark:bg-slate-800/30">
        <!-- Desktop Sidebar Toggle -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="hidden lg:flex items-center gap-3 w-full px-3 py-2 text-slate-500 hover:text-brand-teal transition-all rounded-lg group"
                :class="sidebarCollapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
            <span x-show="!sidebarCollapsed" class="text-sm font-medium">Collapse Sidebar</span>
        </button>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 py-2 w-full rounded-lg text-slate-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-600 transition-all group"
                    :class="sidebarCollapsed ? 'justify-center' : ''">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span x-show="!sidebarCollapsed" class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>