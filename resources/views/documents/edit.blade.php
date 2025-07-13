<x-layouts.app :title="__('Document')">
    <flux:heading size="xl" level="1">{{ __('Edit Document') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Edit document
    </flux:text>
    <flux:separator variant="subtle" />

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-6 w-full">
        <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-y-4 mt-6 w-md">
            <flux:input label="Document Name" name="name" value="{{ old('name', $document->name) }}" />
            <flux:textarea label="Document Description" name="description" value="{{ old('description', $document->description) }}" />
            <flux:input label="File" type="file" name="file" value="{{ old('file_path', $document->file_path) }}" />
        </div>

        <div class="flex justify-between items-center mt-10">
            <a href="{{ route('documents.index') }}" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Back</a>
            <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Update Document</button>
        </div>

        </form>
    </div>
</x-layouts.app>