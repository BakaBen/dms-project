<x-layouts.app :title="__('Document Management')">
    <flux:heading size="xl" level="1">{{ __('Rejected Documents') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Manage your documents
    </flux:text>
    <flux:separator variant="subtle" />

    <div class="flex justify-end mt-2">
        @can('create documents')
            <a href="{{ route('documents.create') }}" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Create Document</a>
        @endcan
    </div>

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
                        @elseif ($document->status == 'approved')
                            <flux:badge color="green">Approved</flux:badge>
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
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">Action</flux:button>
                            <flux:menu>
                                @can('read documents')
                                <flux:menu.item href="{{ route('documents.show', $document) }}" icon="eye">View</flux:menu.item>
                                @endcan
                                @can('update documents')
                                <flux:menu.item href="{{ route('documents.edit', $document) }}" icon="pencil">Revision</flux:menu.item>
                                @endcan
                                @can('rollback documents')
                                <form id="rollback-form-{{ $document->id }}" action="{{ route('documents.rollback', $document) }}" method="POST">
                                    @csrf
                                    <flux:menu.item 
                                        icon="arrow-path" 
                                        onclick="event.preventDefault(); if(confirm('Are you sure you want to rollback?')) { document.getElementById('rollback-form-{{ $document->id }}').submit(); }">
                                        Rollback
                                    </flux:menu.item>
                                </form>
                                @endcan
                                @can('view previous versions')
                                <flux:menu.item href="{{ route('documents.previous', $document) }}" icon="arrow-left">Previous Version</flux:menu.item>
                                @endcan
                                
                                @can('activate approval')
                                <flux:menu.submenu heading="Enable Approval">
                                    <flux:menu.radio.group>
                                        @if(!$document->is_approvable)
                                        <form id="enable-form-{{ $document->id }}" action="{{ route('documents.enableApproval', $document) }}" method="POST">
                                            @csrf
                                            <flux:menu.radio 
                                                onclick="event.preventDefault(); if(confirm('Are you sure you want to enable approval?')) { document.getElementById('enable-form-{{ $document->id }}').submit(); }">
                                                Enable
                                            </flux:menu.radio>
                                        </form>
                                        @else
                                        <form id="disable-form-{{ $document->id }}" action="{{ route('documents.disableApproval', $document) }}" method="POST">
                                            @csrf
                                            <flux:menu.radio 
                                                onclick="event.preventDefault(); if(confirm('Are you sure you want to disable approval?')) { document.getElementById('disable-form-{{ $document->id }}').submit(); }">
                                                Disable
                                            </flux:menu.radio>
                                        </form>
                                        @endif
                                    </flux:menu.radio.group>
                                </flux:menu.submenu>
                                @endcan

                                @can('delete documents')
                                <form id="delete-form-{{ $document->id }}" action="{{ route('documents.destroy', $document) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <flux:menu.item 
                                        icon="trash" 
                                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete?')) { document.getElementById('delete-form-{{ $document->id }}').submit(); }">
                                        Delete
                                    </flux:menu.item>
                                </form>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $documents->links() }}
    </div>

</x-layouts.app>