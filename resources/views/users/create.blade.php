<x-layouts.app :title="__('User')">
    <flux:heading size="xl" level="1">{{ __('Create Users') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Add user
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
        <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <flux:fieldset>
            <flux:input label="Name" name="name" value="{{ old('name') }}" class="max-w-sm" />
            <flux:input label="Email" name="email" type="email" value="{{ old('email') }}" class="max-w-sm" />
            <flux:select label="Role" name="roles" class="max-w-sm">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </flux:select>
            <div class="grid grid-cols-2 gap-x-4 gap-y-6 max-w-xl">
                <flux:input label="Password" name="password" type="password" value="{{ old('password') }}" viewable />
                <flux:input label="Password Confirm" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" viewable />
            </div>
        </flux:fieldset>

        <div class="flex justify-between items-center mt-10">
            <a href="{{ route('users.index') }}" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Back</a>
            <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Create User</button>
        </div>

        </form>
    </div>
</x-layouts.app>