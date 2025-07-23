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
                <flux:text>{{ $document->currentVersion->version_number }}</flux:text>
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
                @elseif ($document->status == 'approved')
                    <flux:badge class="mt-4" color="green">Approved</flux:badge>
                @elseif ($document->status == 'rejected')
                    <flux:badge class="mt-4" color="red">Rejected</flux:badge>
                    <div class="col-span-2 grid grid-cols-subgrid py-4">
                        <flux:heading>Reason</flux:heading>
                        <flux:text>{{ $document->reject_notes }}</flux:text>
                    </div>
                @else
                    <flux:badge class="mt-4" color="blue">No Status</flux:badge>    
                @endif
            </div>
        </div>
        @if($document->status === 'unvalidated' && $document->is_approvable)
        @can('approve document')
            <form method="POST" action="{{ route('documents.approve', $document) }}" style="display:inline-block;">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Approve</button>
            </form>
        @endcan

        @can('reject document')
            {{-- Tombol tolak tampilkan kolom komentar saat diklik --}}
            <button onclick="toggleRejectForm()" class="bg-red-600 text-white  px-4 py-2 rounded">Reject</button>

            {{-- Form reject tersembunyi awalnya --}}
            <form method="POST" action="{{ route('documents.reject', $document) }}" id="reject-form" style="display: none; margin-top: 10px;">
                @csrf
                <label for="reject_notes">Rejection Notes:</label>
                <textarea name="reject_notes" id="reject_notes" required class="w-full border rounded p-2 mt-2 text-black" placeholder="Write your rejection notes here"></textarea>
                <br>
                <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded mt-2">Send Rejection Note</button>
            </form>

            <script>
                function toggleRejectForm() {
                    const form = document.getElementById('reject-form');
                    form.style.display = form.style.display === 'none' ? 'block' : 'none';
                }
            </script>
        @endcan
        @endif
        <!-- <div class="mt-4">
            <iframe src="{{ asset('storage/' . $document->file_path) }}" width="100%" height="600px"></iframe>
        </div> -->

        @php
            $fileExtension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        @endphp

        @if (strtolower($fileExtension) === 'pdf')
            <div class="mt-4">
                <iframe src="{{ asset('storage/' . $document->file_path) }}" width="100%" height="600px"></iframe>
            </div>
        @else
            <div class="mt-4">
                <p><strong>No preview available for this file type.</strong></p>
                <a href="{{ asset('storage/' . $document->file_path) }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" download>
                    Download Document
                </a>
            </div>
        @endif
    </div>

    @if($document->status == 'approved')
        <div class="mt-4"></div>
    @else
        @include('comment')
    @endif
</x-layouts.app>