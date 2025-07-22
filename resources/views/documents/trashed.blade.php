<x-layouts.app :title="__('Document Management')">
    <flux:heading size="xl" level="1">{{ __('Trashed Documents') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Restore or permanently delete your documents
    </flux:text>
    <flux:separator variant="subtle" />

    <div class="relative overflow-x-auto rounded-md mt-2">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-600 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Author
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr class=" odd:bg-white odd:dark:bg-zinc-800 even:bg-gray-50 even:dark:bg-zinc-700 border-b dark:border-zinc-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $document->name }}
                    </th>
                    <td class="px-6 py-4">
                        @if ($document->status == 'unvalidated')
                            <flux:badge color="yellow">Unvalidated</flux:badge>
                        @elseif ($document->status == 'validated')
                            <flux:badge color="green">Validated</flux:badge>
                        @elseif ($document->status == 'rejected')
                            <flux:badge color="red">Rejected</flux:badge>
                        @else
                            <flux:badge color="blue">No Status</flux:badge>    
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{ $document->user->name ?? '-' }}
                    </td>
                    <td class="flex items-center gap-2 px-6 py-4">
                        @can('restore documents')
                        <form action="{{ route('documents.restore', $document) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-900" onclick="return confirm('Are you sure?')">Restore</button>
                        </form>
                        @endcan
                        @can('delete documents')
                        <form action="{{ route('documents.force-delete', $document->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $documents->links() }}
    </div>

</x-layouts.app>