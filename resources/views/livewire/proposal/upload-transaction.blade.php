<div>
    <x-modal.card title="Bukti Transfer" align="center" blur wire:model.defer="uploadModal"
        x-on:close="Livewire.emit('view', 'list')">
        @if ($view == 'list')
        Riwayat Transfer
        <ul class="grid gap-3 mb-3">
            @if ($transactions)
            @foreach ($transactions as $tx)
            <li>
                <x-button xs label="{{ $tx->file->name }}" href="{{ asset('storage/'.$tx->file->file_path.'/'.$tx->file->file_name) }}"
                    target="_blank" />
            </li>
            @endforeach
            @endif
        </ul>
        <x-button positive spinner label="Upload bukti transfer" wire:click="$set('view', 'form')" />
        @elseif ($view == 'form')
        <form class="grid grid-cols-1 gap-2" wire:submit.prevent="save">
            <div class="grid grid-cols-2 gap-4 border rounded p-2">
                <x-input label="Bank" wire:model.lazy="bankName" />
                <x-input label="Nama Pemilik Rekening" wire:model.lazy="accountName" />
                <x-input label="Nomor Rekening" wire:model.lazy="accountNumber"
                    x-mask="9999 9999 9999 9999 9999 9999" />
                <x-inputs.currency label="Nominal Transfer" placeholder="Nominal Transfer" thousands="." decimal=","
                    wire:model.lazy="amount" />
            </div>
            <div class="grid grid-cols-2 gap-4 border rounded p-2">
                <x-select label="Rekening Sumber" placeholder="Pilih salah satu" :options="$banks" option-label="name"
                    option-value="id" wire:model.defer="bank" />
                <x-input name="fileName" id="fileName" label="Nama File"
                    placeholder="Nama File" wire:model="fileName" />
                <div class="col-span-2" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <!-- File Input -->
                    <x-input type="file" label="File" name="file" id="file" wire:model="file" />
                    @error('file') <span class="text-sm text-negative-500">{{ $message }}</span>
                    @enderror

                    <!-- Progress Bar -->
                    <div x-show="isUploading">
                        <progress max="100" x-bind:value="progress"></progress>
                    </div>
                </div>
            </div>
            <x-button primary label="Simpan" wire:click="save" x-on:close-modal.window="close" />
        </form>
        @endif
    </x-modal.card>
</div>