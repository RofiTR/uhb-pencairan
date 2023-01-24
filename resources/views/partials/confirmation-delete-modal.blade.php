<x-modal.card title="Konfirmasi Hapus" align="center" blur wire:model.defer="deleteModal">
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <span>Anda yakin akan menghapus data ini?</span>
    <x-checkbox id="force-delete" label="Hapus permanen" wire:model.defer="permanently" />
  </div>

  <x-slot name="footer">
    <div class="flex justify-between gap-x-4">
      <x-button negative label="Hapus" wire:click="delete" x-on:click="close" />

      <div class="flex">
        <x-button primary label="Batal" x-on:click="close" />
      </div>
    </div>
  </x-slot>
</x-modal.card>