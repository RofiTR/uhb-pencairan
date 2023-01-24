<div class="grid grid-cols-1 gap-2">
    @foreach ($configurations as $key => $value)
    <x-card>
        <form wire:submit.prevent="save('{{ $key }}', '{{ $value['id'] }}')">
        <div class="grid grid-cols-4 gap-2">
            <x-input label="Group" readonly wire:model="configurations.{{ $key }}.group" />
            @if ($value['group'] == 'approver')
            <x-input label="Limit" wire:model="configurations.{{ $key }}.name" />
            <x-select label="Pimpinan" :options="$users" option-label="label" option-value="value"
                wire:model.defer="configurations.{{ $key }}.value" />
            @else
            <x-input label="Name" wire:model="configurations.{{ $key }}.name" />
            <x-input label="Value" wire:model="configurations.{{ $key }}.value" />
            @endif
            <x-button.circle primary icon="save" wire:click="save('{{ $key }}', '{{ $value['id'] }}')" />
        </div>
    </form>
    </x-card>
    @endforeach
</div>