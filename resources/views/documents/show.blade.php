<x-layouts.app :title="__('Document Management')">
    <flux:heading size="xl" level="1">{{ __('Documents') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Details of {{ $document->name }}
    </flux:text>
    <flux:separator variant="subtle" />
    
    @include('comment')
</x-layouts.app>