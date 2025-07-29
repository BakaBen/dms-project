<x-layouts.app :title="__('Dashboard')">
    <flux:heading size="xl" level="1">{{ __('Welcome :name', ['name' => auth()->user()->name]) }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        {{ __('You are logged in!') }}
    </flux:text>
    <flux:separator variant="subtle" />

    <div class="flex w-full flex-1 flex-col mt-4 gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative h-32 w-full aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('documents.published') }}" class="block w-full h-full p-6 bg-white rounded-lg shadow-sm hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                    <flux:heading size="lg">Published Documents</flux:heading>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $status['approved'] }}</div>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400">
                            <flux:icon.check />
                        </div>                    
                    </div>
                </a>
            </div>
            @hasanyrole('admin|author')
            <div class="relative h-32 w-full aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('documents.index') }}" class="block w-full h-full p-6 bg-white rounded-lg shadow-sm hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                    <flux:heading size="lg">Pending Documents</flux:heading>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $status['unvalidated'] }}</div>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-600 dark:text-yellow-400">
                            <flux:icon.exclamation-triangle />
                        </div>                    
                    </div>
                </a>
            </div>
            <div class="relative h-32 w-full aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('documents.rejected') }}" class="block w-full h-full p-6 bg-white rounded-lg shadow-sm hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                    <flux:heading size="lg">Rejected Documents</flux:heading>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $status['rejected'] }}</div>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                            <flux:icon.no-symbol />
                        </div>                    
                    </div>
                </a>
            </div>
            @endhasanyrole
        </div>
    </div>
    <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Document Upload Statistics</h2>
            <canvas id="weeklyChart" height="200"></canvas>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">User Role Statistics</h2>
            <canvas id="roleChart" height="100"></canvas>
        </div>
    </div> -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Document Upload Statistics</h2>
            <div class="h-64">
                <canvas id="weeklyChart" class="w-full h-full"></canvas>
            </div>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-zinc-800 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">User Role Statistics</h2>
            <div class="h-64">
                <canvas id="roleChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>



    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Total weekly upload',
                    data: @json($data),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        const ctx2 = document.getElementById('roleChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: @json($roles->keys()),
                datasets: [{
                    data: @json($roles->values()),
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
    </script>
</x-layouts.app>
