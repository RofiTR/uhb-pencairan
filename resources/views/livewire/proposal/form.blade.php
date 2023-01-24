<div>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-2 gap-3 mb-3">
            <x-select label="Jenis Pengajuan" placeholder="Pilih salah satu" :options="$types" option-label="name"
                option-value="id" wire:model.defer="type" />
            <x-input label="Nama Pengajuan" placeholder="Nama Pengajuan" wire:model.defer="name" />
            <x-textarea label="Deskripsi" placeholder="Deskripsi singkat pengajuan" wire:model.defer="description" />
            <x-inputs.currency label="Nominal Pengajuan" placeholder="Nominal Pengajuan" thousands="." decimal="," wire:model.lazy="amount" />
            <x-select label="Pimpinan" placeholder="Pilih salah satu" :options="$approvers" option-label="label"
                option-value="value" wire:model.defer="approver" />
            <x-select label="Metode Pencairan" placeholder="Pilih salah satu" :options="['Tunai', 'Transfer']"
                wire:model.lazy="withdrawal" />
            <div class="grid grid-cols-3 gap-3 content-start border-2 rounded p-2">
                <div class="col-span-3 mt-2 italic">File lampiran berupa JPG/PNG/BMP/PDF</div>
                @php $no = 1; @endphp
                @foreach ($inputs as $key => $value)
                <div>
                    <x-input name="fileNames[]" id="fileNames.{{ $key }}" label="Nama File (opsional)"
                        placeholder="Nama File (opsional)" wire:model="fileNames.{{ $key }}" />
                </div>
                <div>
                    <label for="files.{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Lampiran {{ $no++ }}
                    </label>
                    <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <!-- File Input -->
                        <x-input type="file" name="files[]" id="files.{{ $key }}" wire:model="files.{{ $key }}" />
                        @error('files.'.$key) <span class="text-sm text-negative-500">{{ $message }}</span>
                        @enderror

                        <!-- Progress Bar -->
                        <div x-show="isUploading">
                            <progress max="100" x-bind:value="progress"></progress>
                        </div>
                    </div>
                </div>
                <x-imui-button type="button" class="bg-red-500 text-white w-6 h-6 rounded-lg"
                    wire:click.prevent="remove({{ $key }})">X</x-imui-button>
                @endforeach
                <x-imui-button type="button" class="w-fit" wire:click.prevent="add({{ $i }})">Tambah Lampiran
                </x-imui-button>
            </div>
            @if ($withdrawal == 'Transfer')
            <div class="grid grid-cols-1 gap-3 content-start border-2 rounded p-2">
                <x-select class="col-span-2" label="Rekening Tersimpan" placeholder="Pilih salah satu" :options="$banks"
                    option-label="label" option-value="value" wire:model.lazy="bank" />
                <x-input label="Nama Pemilik" placeholder="Nama Pemilik" wire:model.defer="accountName" />
                <x-input label="Nama Alias" placeholder="Nama Alias" wire:model.defer="accountAlias" />
                <x-input label="Nama Bank" placeholder="Nama Bank" wire:model.defer="bankName" />
                <x-input label="No. Rekening" placeholder="No. Rekening" wire:model.defer="accountNumber"
                    x-mask="9999 9999 9999 9999 9999 9999" />
                <x-checkbox id="right-label" label="Simpan rekening" wire:model.defer="saveAccount" />
            </div>
            @endif
        </div>
        <x-imui-button class="bg-indigo-500 text-white">Save</x-imui-button>
    </form>
</div>