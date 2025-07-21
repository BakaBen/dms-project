<x-layouts.app :title="__('Document Management')">
    <flux:heading size="xl" level="1">{{ __('Documents') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        View detailed information about the selected document
    </flux:text>
    <flux:separator variant="subtle" />
    
    <div class="mt-6">
        <flux:heading size="lg" level="2" class="mb-4">Document Information</flux:heading>
        <div class="py-6 grid grid-cols-[20%_1fr] gap-y-4 border-t border-gray-200 divide-y divide-gray-200">
            <!-- Title -->
            <div class="col-span-2 grid grid-cols-subgrid py-4">
                <flux:heading>Title</flux:heading>
                <flux:text>{{ $document->name }}</flux:text>
            </div>

            <!-- Description -->
            <div class="col-span-2 grid grid-cols-subgrid py-4">
                <flux:heading>Description</flux:heading>
                <flux:text>{{ $document->description }}</flux:text>
            </div>

            <!-- Version -->
            <div class="col-span-2 grid grid-cols-subgrid py-4">
                <flux:heading>Version</flux:heading>
                <flux:text>{{ $version->version_number }}</flux:text>
            </div>

            <!-- Submission Date -->
            <div class="col-span-2 grid grid-cols-subgrid py-4">
                <flux:heading>Submission Date</flux:heading>
                <flux:text>{{ $document->created_at }}</flux:text>
            </div>

            <!-- Status -->
            <div class="py-4">
                <flux:heading>Status</flux:heading>
                @if ($document->status == 'unvalidated')
                    <flux:badge class="mt-4" color="yellow">Unvalidated</flux:badge>
                @elseif ($document->status == 'validated')
                    <flux:badge class="mt-4" color="green">Validated</flux:badge>
                @elseif ($document->status == 'rejected')
                    <flux:badge class="mt-4" color="red">Rejected</flux:badge>
                @else
                    <flux:badge class="mt-4" color="blue">No Status</flux:badge>    
                @endif
            </div>
        </div>

        <div class="mt-4">
            <iframe src="{{ asset('storage/' . $version->file_path) }}" width="100%" height="600px"></iframe>
        </div>
    </div>

    @include('comment')
</x-layouts.app>