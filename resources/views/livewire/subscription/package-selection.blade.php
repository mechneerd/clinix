<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 py-12 px-4">

    {{--
        FIX: Removed `overflow-hidden` from the decorative wrapper div.
        The blobs use `fixed` positioning so they escape any parent's overflow
        clipping anyway — the overflow-hidden was a no-op at best, and on some
        browsers it created a new stacking context that pushed the blobs behind
        the content. They now render correctly over the full viewport.
    --}}
    <div class="fixed inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-indigo-600/15 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-violet-600/15 blur-[120px]"></div>
    </div>

    <div class="relative max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-medium mb-4">
                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></div>
                Welcome to Clinix, {{ auth()->user()->name }}!
            </div>
            <h1 class="text-4xl font-bold text-white tracking-tight mb-3">Choose your plan</h1>
            <p class="text-slate-400 text-lg max-w-xl mx-auto">
                Start with a 14-day free trial on paid plans. No credit card required.
            </p>

            {{-- Billing Toggle --}}
            <div class="inline-flex items-center gap-3 mt-6 p-1 rounded-xl bg-white/5 border border-white/10">
                <button wire:click="$set('billing','monthly')"
                        @class([
                            'px-5 py-2 rounded-lg text-sm font-medium transition-all',
                            'bg-white text-slate-900 shadow-sm' => $billing === 'monthly',
                            'text-slate-400 hover:text-white' => $billing !== 'monthly',
                        ])>
                    Monthly
                </button>
                <button wire:click="$set('billing','yearly')"
                        @class([
                            'px-5 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2',
                            'bg-white text-slate-900 shadow-sm' => $billing === 'yearly',
                            'text-slate-400 hover:text-white' => $billing !== 'yearly',
                        ])>
                    Yearly
                    <span class="text-xs px-1.5 py-0.5 rounded-md bg-green-500/20 text-green-400 font-semibold">Save 17%</span>
                </button>
            </div>
        </div>

        {{-- Plans Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @foreach ($tiers as $tier)
                @php
                    $isSelected = $selectedId === $tier->id;
                    $isPopular  = $tier->slug === 'basic';
                    $price      = $billing === 'yearly' ? $tier->yearly_price / 12 : $tier->monthly_price;
                    $features   = $tier->features();
                @endphp
                <div wire:click="selectPlan({{ $tier->id }})"
                     @class([
                         'relative flex flex-col rounded-3xl border-2 p-7 cursor-pointer transition-all duration-200',
                         'bg-white/10 border-indigo-500 shadow-2xl shadow-indigo-500/20 scale-[1.02]' => $isSelected,
                         'bg-white/5 border-indigo-500/30' => !$isSelected && $isPopular,
                         'bg-white/5 border-white/10 hover:border-white/20' => !$isSelected && !$isPopular,
                     ])>

                    {{-- Popular badge --}}
                    @if ($isPopular)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                            <span class="px-4 py-1 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 text-white text-xs font-bold shadow-lg">
                                ✦ Most Popular
                            </span>
                        </div>
                    @endif

                    {{-- Selected indicator --}}
                    @if ($isSelected)
                        <div class="absolute top-4 right-4 w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center shadow-md">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Plan name --}}
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-xl font-bold text-white">{{ $tier->name }}</h2>
                        </div>
                        <p class="text-slate-400 text-sm">{{ $tier->description }}</p>
                    </div>

                    {{-- Price --}}
                    <div class="mb-6">
                        @if ($tier->isFree())
                            <div class="flex items-end gap-1">
                                <span class="text-4xl font-extrabold text-white">Free</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Forever, no credit card needed</p>
                        @else
                            <div class="flex items-end gap-1">
                                <span class="text-slate-400 text-xl font-medium">$</span>
                                <span class="text-4xl font-extrabold text-white">{{ number_format($price, 0) }}</span>
                                <span class="text-slate-400 text-sm mb-1">/mo</span>
                            </div>
                            @if ($billing === 'yearly')
                                <p class="text-xs text-green-400 mt-1">
                                    ${{ number_format($tier->yearly_price, 2) }}/year
                                    · Save ${{ number_format($tier->yearlySavings, 0) }}
                                </p>
                            @else
                                <p class="text-xs text-slate-500 mt-1">{{ $tier->trial_days }}-day free trial included</p>
                            @endif
                        @endif
                    </div>

                    {{-- Features --}}
                    <ul class="space-y-2.5 flex-1 mb-6">
                        @foreach ($features as $feature)
                            <li class="flex items-start gap-2.5 text-sm text-slate-300">
                                <svg class="w-4 h-4 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    {{-- CTA --}}
                    <button @class([
                        'w-full py-2.5 rounded-xl text-sm font-semibold transition-all',
                        'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' => $isSelected,
                        'bg-white/10 text-white hover:bg-white/20' => !$isSelected,
                    ])>
                        @if ($isSelected) ✓ Selected @else Select Plan @endif
                    </button>

                </div>
            @endforeach
        </div>

        {{-- Error --}}
        @error('plan')
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center">
                {{ $message }}
            </div>
        @enderror

        {{-- Confirm Button --}}
        <div class="text-center">
            <flux:button
                wire:click="confirmSelection"
                variant="primary"
                wire:loading.attr="disabled"
                @class([
                    'px-10 py-3 bg-gradient-to-r from-indigo-500 to-violet-600 border-0 text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all text-base',
                    'opacity-50 cursor-not-allowed' => !$selectedId,
                ])>
                <span wire:loading.remove wire:target="confirmSelection">
                    @if ($selectedId)
                        Get Started with {{ $tiers->firstWhere('id', $selectedId)?->name }} →
                    @else
                        Select a plan to continue
                    @endif
                </span>
                <span wire:loading wire:target="confirmSelection" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Activating your plan…
                </span>
            </flux:button>

            <p class="text-xs text-slate-500 mt-4">
                🔒 Secure · Cancel anytime · No hidden fees
            </p>
        </div>

        {{-- Trust badges --}}
        <div class="flex items-center justify-center gap-8 mt-12 opacity-50">
            <div class="text-center">
                <div class="text-2xl font-bold text-white">500+</div>
                <div class="text-xs text-slate-400">Clinics</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">50k+</div>
                <div class="text-xs text-slate-400">Patients</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">99.9%</div>
                <div class="text-xs text-slate-400">Uptime</div>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">HIPAA</div>
                <div class="text-xs text-slate-400">Compliant</div>
            </div>
        </div>

    </div>
</div>