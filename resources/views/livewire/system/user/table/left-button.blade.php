<x-button fuschia icon="refresh" wire:click="$refresh" />
<x-button positive icon="plus" onclick="$openModal('addModal')" />

<x-modal.card title="Add User" align="center" blur wire:model.defer="addModal">
  <div class="grid grid-cols-2 gap-3 mb-3">
    <x-select label="User" placeholder="Pilih user" multiselect :async-data="[
        'api' => route('api.users.dapo'),
        'method' => 'POST'
    ]" option-label="label" option-value="value" wire:model.defer="user" />
    <x-select label="Role" placeholder="Pilih role" multiselect :options="$roles" option-label="name"
      option-value="name" wire:model.defer="role" />
  </div>

  <x-slot name="footer">
    <div class="flex justify-between gap-x-4">
      <x-button primary label="Save" wire:click="save" x-on:click="close" />

      <div class="flex">
        <x-button negative label="Batal" x-on:click="close" />
      </div>
    </div>
  </x-slot>
</x-modal.card>