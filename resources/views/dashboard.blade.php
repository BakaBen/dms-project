<x-layouts.app :title="__('Dashboard')">
    <flux:heading size="xl" level="1">{{ __('Welcome :name', ['name' => auth()->user()->name]) }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        {{ __('You are logged in!') }}
    </flux:text>
    <flux:separator variant="subtle" />

    <div class="flex w-full flex-1 flex-col mt-4 gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative h-32 w-full aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('documents.index') }}" class="block w-full h-full p-6 bg-white rounded-lg shadow-sm hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                    <flux:heading size="lg">Published Documents</flux:heading>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $approved }}</div>
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
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $unvalidated }}</div>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-600 dark:text-yellow-400">
                            <flux:icon.exclamation-triangle />
                        </div>                    
                    </div>
                </a>
            </div>
            <div class="relative h-32 w-full aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <a href="{{ route('documents.index') }}" class="block w-full h-full p-6 bg-white rounded-lg shadow-sm hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                    <flux:heading size="lg">Rejected Documents</flux:heading>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $rejected }}</div>
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
</x-layouts.app>
