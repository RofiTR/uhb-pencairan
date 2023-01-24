<div>
    <x-card title="Quick Actions">
        <x-button primary label="Link Storage" wire:click="linkStorage" wire:ignore />
        <x-button primary label="Clear Cache" wire:click="clearCache" wire:ignore />
        <x-button primary label="Dump SQL" wire:click="dumpSql" wire:ignore />
        <x-button primary label="Update Sistem" wire:click="updateApp" wire:ignore />
    </x-card>

    <x-card title="Artisan Command" cardClasses="mt-4">
        <form class="grid grid-rows-1 gap-4">
            @csrf
            <x-input label="Command" placeholder="eg: down" wire:model="method" />
            @foreach($inputs as $key => $value)
            <div class="flex flex-row justify-between my-2">
                <div class="flex-1">
                    <x-input label="Key" placeholder="eg: --secret" wire:model="keys.{{ $value }}" />
                </div>
                <div class="flex-1">
                    <x-input label="Value" placeholder="eg: 123" wire:model="values.{{ $value }}" />
                </div>
                <x-button warning label="X" wire:click.prevent="remove({{ $key }})" />
            </div>
            @endforeach
            <div class="w-1/4">
                <x-button info icon="plus" wire:click.prevent="add({{ $i }})" />
            </div>
            <div class="w-1/4">
                <x-button negative label="Run" wire:click.prevent="store()" />
            </div>
        </form>
    </x-card>
</div>