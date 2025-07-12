<x-layouts.app :title="__('Roles')">
    <flux:heading size="xl" level="1">{{ __('Edit Role') }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">
        Edit a role permissions
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

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mt-6 w-md">
            <flux:input label="Role Name" name="name" value="{{ $role->name }}" />
        </div>
        <div class="mt-6 w-full">
            <flux:fieldset class="mt-6">
                <flux:legend>Permissions</flux:legend>
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($permissions as $permission)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission-{{ $permission->id }}"
                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </flux:fieldset>
        </div>
        <div class="flex justify-between items-center mt-10">
            <a href="{{ route('roles.index') }}" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Back</a>
            <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Save</button>
        </div>
    </form>
</x-layouts.app>