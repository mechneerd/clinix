<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Profile Settings') }}</h1>
            <p class="text-slate-400">{{ __('Manage your account information and security') }}</p>
        </div>

        <div class="grid gap-6">
            <div class="p-4 sm:p-8 bg-slate-800/50 border border-slate-700 backdrop-blur-sm shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-slate-800/50 border border-slate-700 backdrop-blur-sm shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-rose-500/5 border border-rose-500/20 backdrop-blur-sm shadow-xl rounded-2xl">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
