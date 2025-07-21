<x-layouts.app :title="__('Document Management')">
    <flux:heading size="xl" level="1">{{ __('Documents') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Manage your documents
    </flux:text>
    <flux:separator variant="subtle" />

    <div class="relative overflow-x-auto rounded-md mt-2">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-600 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Version
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
                @foreach($previousVersion as $version)
                <tr class=" odd:bg-white odd:dark:bg-zinc-800 even:bg-gray-50 even:dark:bg-zinc-700 border-b dark:border-zinc-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $version->version_number }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $version->user->name }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('documents.show.previous', ['document' => $document, 'version' => $version->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>