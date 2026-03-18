<div class="space-y-6 pb-20" x-data="{ 
    stats: @js($stats),
    revenueChartData: @js($revenueChart)
}" x-init="initCharts()">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Platform Overview</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Managing the Clinix ecosystem analytics and growth.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('super-admin.packages.create') }}" wire:navigate class="px-5 py-2.5 bg-brand-teal text-white rounded-xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all text-sm">
                New Package
            </a>
            <a href="{{ route('super-admin.clinics.create') }}" wire:navigate class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-white rounded-xl font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm shadow-sm">
                Add Clinic
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach([
            ['key' => 'total_revenue', 'label' => 'Total Revenue', 'icon' => 'dollar', 'color' => 'brand-teal', 'prefix' => '$', 'trend' => '+14%'],
            ['key' => 'total_clinics', 'label' => 'Total Clinics', 'icon' => 'building', 'color' => 'brand-teal', 'trend' => '+8%'],
            ['key' => 'total_patients', 'label' => 'Total Patients', 'icon' => 'patient', 'color' => 'brand-green', 'trend' => '+22%'],
            ['key' => 'total_appointments', 'label' => 'Total Appts', 'icon' => 'calendar', 'color' => 'brand-teal', 'trend' => '+12%'],
        ] as $stat)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 relative group overflow-hidden shadow-sm">
            <div class="flex flex-col relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}/10 flex items-center justify-center text-{{ $stat['color'] }}">
                        <x-icons :name="$stat['icon']" class="w-6 h-6" />
                    </div>
                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold {{ str_contains($stat['trend'], '+') ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                        {{ $stat['trend'] }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $stat['label'] }}</p>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                        {{ ($stat['prefix'] ?? '') . number_format($stats[$stat['key']]) }}
                    </h3>
                </div>
            </div>
            <!-- Subtitle background icon -->
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] dark:opacity-[0.05] pointer-events-none group-hover:scale-110 transition-transform duration-500">
                <x-icons :name="$stat['icon']" class="w-32 h-32" />
            </div>
        </div>
        @endforeach
    </div>

    <!-- Main Content Grid -->
    <div class="grid xl:grid-cols-3 gap-6">
        
        <!-- Revenue Analytics -->
        <div class="xl:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest">Revenue Flow</h3>
                    <p class="text-xl font-bold text-slate-900 dark:text-white mt-1">Growth Performance</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-brand-teal"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Current</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-700"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Previous</span>
                    </div>
                </div>
            </div>
            
            <div class="h-[350px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- System Health & Distribution -->
        <div class="space-y-6">
            <!-- Platform Stats -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
                <h3 class="font-black text-slate-900 dark:text-white mb-6 flex items-center gap-2 uppercase text-sm tracking-wider">
                    <svg class="w-5 h-5 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Performance Metrics
                </h3>
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="text-slate-500 font-bold uppercase tracking-tighter">Active Clinics</span>
                            <span class="text-brand-teal font-black text-sm">{{ $stats['active_clinics'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-teal" style="width: {{ $stats['total_clinics'] > 0 ? min(100, ($stats['active_clinics']/$stats['total_clinics'])*100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="text-slate-500 font-bold uppercase tracking-tighter">Doctor Engagement</span>
                            <span class="text-brand-green font-black text-sm">{{ $stats['total_doctors'] }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-green" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Alerts -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
                <h3 class="font-black text-slate-900 dark:text-white mb-6 flex items-center justify-between uppercase text-sm tracking-wider">
                    <span>System Integrity</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                </h3>
                <div class="space-y-4">
                    @if(count($healthAlerts) > 0)
                        @foreach($healthAlerts as $alert)
                        <div class="flex gap-4 p-4 rounded-2xl bg-rose-50 dark:bg-rose-500/5 border border-rose-100 dark:border-rose-500/10">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight">{{ $alert->name }}</p>
                                <p class="text-[10px] font-bold uppercase text-rose-500 mt-1">Payment Overdue</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="py-6 text-center">
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-slate-900 dark:text-white font-bold text-sm">All Systems Go</p>
                            <p class="text-slate-500 text-[10px] uppercase font-bold mt-1 tracking-tighter">No critical alerts detected</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Clinics Distribution -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-8 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest">Ecosystem Hub</h3>
                <p class="text-xl font-bold text-slate-900 dark:text-white mt-1">Medical Centers Performance</p>
            </div>
            <a href="{{ route('super-admin.clinics') }}" wire:navigate class="px-4 py-2 text-xs font-bold text-brand-teal hover:bg-brand-teal/5 rounded-lg transition-all uppercase tracking-wider">Expand Report</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Clinic Identity</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Doctors</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Staff</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Patients</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Activity Level</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($recentClinics as $clinic)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800/50 flex items-center justify-center border border-slate-200 dark:border-slate-700 group-hover:border-brand-teal/50 transition-all shadow-sm">
                                    @if($clinic->logo)
                                        <img src="{{ asset('storage/'.$clinic->logo) }}" class="w-8 h-8 rounded-lg object-contain">
                                    @else
                                        <span class="text-brand-teal font-black text-xl">{{ substr($clinic->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $clinic->name }}</p>
                                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-tight mt-1">{{ $clinic->package->name }} Plan</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 rounded-lg bg-brand-teal/10 text-brand-teal font-black text-xs border border-brand-teal/20">{{ $clinic->doctor_count }}</span>
                        </td>
                        <td class="px-6 py-5 text-center text-slate-600 dark:text-slate-400 font-bold text-sm">{{ $clinic->staff_count }}</td>
                        <td class="px-6 py-5 text-center text-slate-600 dark:text-slate-400 font-bold text-sm">{{ $clinic->patients_count }}</td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="text-slate-900 dark:text-white font-black text-sm">{{ $clinic->appointments_count }}</span>
                                <div class="w-16 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 shadow-inner">
                                    <div class="h-full bg-brand-teal rounded-full" style="width: {{ min(100, $clinic->appointments_count * 5) }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            initCharts();
        });

        function initCharts() {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;
            
            Chart.getChart(ctx)?.destroy();

            const isDark = document.documentElement.classList.contains('dark');
            const tealColor = '#00668F';
            const previousColor = isDark ? '#334155' : '#e2e8f0';

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, isDark ? 'rgba(0, 102, 143, 0.2)' : 'rgba(0, 102, 143, 0.1)');
            gradient.addColorStop(1, 'rgba(0, 102, 143, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @js($revenueChart['labels']),
                    datasets: [
                        {
                            label: 'Current Period',
                            data: @js($revenueChart['current']),
                            borderColor: tealColor,
                            borderWidth: 4,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHitRadius: 10,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: tealColor,
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2
                        },
                        {
                            label: 'Previous Period',
                            data: @js($revenueChart['previous']),
                            borderColor: previousColor,
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#fff',
                            titleColor: isDark ? '#f8fafc' : '#1e293b',
                            bodyColor: isDark ? '#94a3b8' : '#64748b',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            cornerRadius: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: (context) => ' $' + context.parsed.y.toLocaleString()
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: isDark ? 'rgba(148, 163, 184, 0.05)' : 'rgba(226, 232, 240, 0.5)', drawBorder: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { size: 10, weight: 'bold' },
                                callback: (val) => '$' + val.toLocaleString()
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { size: 10, weight: 'bold' }
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>