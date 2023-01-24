<div>
    <form class="grid grid-cols-1 gap-4" wire:submit.prevent="save">
        <div class="border rounded p-2">
            <span class="relative top-[-1.3rem] bg-white px-1">Identitas</span>
            <div class="grid grid-cols-2 gap-3 mt-[-1rem]">
                <div>{{ $user->full_name }}</div>
                <div>{{ $user->email }}</div>
            </div>
        </div>
        <div class="border rounded p-2">
            <span class="relative top-[-1.3rem] bg-white px-1">Roles</span>
            <div class="grid grid-cols-2 gap-3 mt-[-1rem]">
                @foreach ($roles as $key => $perm)
                <x-checkbox id="permission-{{ $key }}" label="{{ str_replace('.', ' ', $perm['name']) }}"
                    value="{{ $perm['name'] }}" wire:model.defer="role.{{ $perm['name'] }}" />
                @endforeach
            </div>
        </div>
        <div class="border rounded p-2">
            <span class="relative top-[-1.3rem] bg-white px-1">Permissions</span>
            <div class="grid grid-cols-4 gap-3 mt-[-1rem]">
                @foreach ($permissions as $key => $perm)
                <x-checkbox id="permission-{{ $key }}" label="{{ str_replace('.', ' ', $perm['name']) }}"
                    value="{{ $perm['name'] }}" wire:model.defer="permission.{{ $perm['name'] }}" />
                @endforeach
            </div>
        </div>
        <x-imui-button class="bg-indigo-500 text-white w-fit">Save</x-imui-button>
    </form>
</div>