<div class="py-8">
    <flux:heading size="lg" level="2">{{ __('Comment') }}</flux:heading>

    @auth
    <div class="mt-6">
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf

            <flux:input name="user_id" type="hidden" value="{{ auth()->user()->id }}" />
            <flux:input name="document_id" type="hidden" value="{{ $document->id }}" />
            <flux:textarea name="comment" />
            <div class="flex justify-end mt-6">
                <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Add Comment</button>
            </div>
        </form>
    </div>
    @endauth

    <div class="mt-6">
        <flux:heading size="lg" level="3">Comments ({{ $document->comments->count() }})</flux:heading>
        @foreach ($document->comments as $comment)
        <div class="flex w-full justify-between">
            <div class="mt-4">
                <flux:text variant="strong">{{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</flux:text>
            </div>
            <div class="mt-4">
                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span class="sr-only">Icon description</span>
                    </button>
                </form>
            </div>
        </div>
                <flux:text class="mt-2">{{ $comment->comment }}</flux:text>
                <flux:separator variant="subtle" class="mt-2" />
        @endforeach
    </div>
</div>