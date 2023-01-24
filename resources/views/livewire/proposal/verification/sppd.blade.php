<div>
    <div class="grid grid-cols-6 gap-3 mb-3">
        @foreach ($inputs as $key => $value)
        <x-imui-button type="button" class="bg-red-500 text-white w-6 h-6 rounded-lg"
            wire:click.prevent="remove({{ $key }})">X</x-imui-button>
        <x-input name="members" id=".{{ $key }}members" label="Anggota" placeholder="Anggota" wire:model="members.{{ $key }}" />
        <x-input name="items" id="items.{{ $key }}" label="Item" placeholder="Item" wire:model="items.{{ $key }}" />
        <x-input name="prices" id="prices.{{ $key }}" label="Harga" placeholder="Harga" wire:model="prices.{{ $key }}" />
        <x-input name="quantities" id="quantities.{{ $key }}" label="Jumlah" placeholder="Jumlah" wire:model="quantities.{{ $key }}" />
        <x-input name="credits" id="credits.{{ $key }}" label="Sub Total" placeholder="Sub Total" wire:model="credits.{{ $key }}" />
        @endforeach
        <x-imui-button type="button" class="w-fit" wire:click.prevent="add({{ $i }})">Tambah Item
        </x-imui-button>
        <x-input name="total" id="total" label="Total" placeholder="Total" wire:model="total" />
    </div>
    <div class="flex justify-between gap-x-4">
        <x-button primary label="Save" wire:click="save" x-on:click="close" />

        <div class="flex">
            <x-button negative label="Batal" x-on:click="close" />
        </div>
    </div>
</div>