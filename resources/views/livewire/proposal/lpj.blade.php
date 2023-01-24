<div class=" bg-white shadow-sm sm:rounded-lg p-6">
    @if ($proposal->report)
    <b class="mt-4">Riwayat LPJ</b>
    @if ($proposal->report->histories)
    <div class="grid grid-cols-1 gap-4">
        <ul>
            @foreach ($proposal->report->histories as $history)
            <li>{{ $history->status->name }}
                {{ $history->created_at }}
                @if($history->notes)
                <br><i>catatan: {{ $history->notes }}</i>
                @endif
            </li>
            @endforeach
        </ul>
        <b class="mt-4">Lampiran</b>
        <ul>
            @foreach ($proposal->report->files as $file)
            <li>
                {{ $file->name }}
                <x-button xs label="Lihat" href="{{ asset('storage/'.$file->file_path.'/'.$file->file_name) }}"
                    target="_blank" />
            </li>
            @endforeach
        </ul>
    </div>
    @endif
    @else
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 gap-4">
            @if ($type->value == 1)
            <div class="grid grid-cols-1 gap-2 content-start border-2 rounded p-2">
                <div class="mt-2 italic">SPPD</div>
                @php $no = 1; @endphp
                <table class="table-auto border-collapse border border-slate-500">
                    <thead>
                        <tr>
                            <td class="p-2 border border-slate-600">No</td>
                            <td class="p-2 border border-slate-600">
                                Tujuan
                            </td>
                            <td class="p-2 border border-slate-600">
                                Waktu Berangkat
                            </td>
                            <td class="p-2 border border-slate-600">
                                Waktu Pulang
                            </td>
                            <td class="p-2 border border-slate-600">
                                Anggota
                            </td>
                            <td class="p-2 border border-slate-600">
                                Hapus
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inputSppd as $key => $value)
                        <tr>
                            <td class="p-2 border border-slate-600">{{ $no++ }}</td>
                            <td class="p-2 border border-slate-600">
                                <x-input name="destinations[]" id="destinations.{{ $key }}" placeholder="Area Tujuan"
                                    wire:model="destinations.{{ $key }}" />
                            </td>
                            <td class="p-2 border border-slate-600">
                                <x-input name="departures[]" id="departures.{{ $key }}" placeholder="Waktu Berangkat"
                                    wire:model="departures.{{ $key }}" readonly />
                            </td>
                            <td class="p-2 border border-slate-600">
                                <x-input name="arrives[]" id="arrives.{{ $key }}" placeholder="Waktu Pulang"
                                    wire:model="arrives.{{ $key }}" readonly />
                            </td>
                            <td class="p-2 border border-slate-600">
                                <x-input name="members[]" id="members.{{ $key }}" placeholder="Anggota"
                                    wire:model="members.{{ $key }}" readonly />
                            </td>
                            <td class="p-2 border border-slate-600">
                                <x-imui-button type="button" class="bg-red-500 text-white w-6 h-6 rounded-lg"
                                    wire:click.prevent="removeSppd({{ $key }})">X</x-imui-button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-imui-button type="button" class="w-fit" onclick="$openModal('addModal')">Tambah Tujuan
                </x-imui-button>
            </div>
            @endif
            <x-input label="Deskripsi singkat laporan" wire:model.lazy="description" />
            <div class="grid grid-cols-3 gap-3 content-start border-2 rounded p-2">
                <div class="col-span-3 mt-2 italic">File lampiran berupa JPG/PNG/BMP/PDF</div>
                @php $no = 1; @endphp
                @foreach ($inputFiles as $key => $value)
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
                    wire:click.prevent="removeAttachment({{ $key }})">X</x-imui-button>
                @endforeach
                <x-imui-button type="button" class="w-fit" wire:click.prevent="addAttachment({{ $i }})">Tambah Lampiran
                </x-imui-button>
            </div>
        </div>
        <x-imui-button class="mt-4 bg-indigo-500 text-white">Save</x-imui-button>
    </form>
    <x-modal.card title="Tambah Tujuan SPPD" align="center" blur wire:model.defer="addModal">
        <div class="grid grid-cols-2 gap-3 mb-3">
            <x-input name="fdestinations" id="fdestinations" label="Area Tujuan" placeholder="Area Tujuan"
                wire:model="fdestinations" />
            <x-datetime-picker without-timezone name="fdepartures" id="fdepartures" label="Waktu Berangkat"
                placeholder="Waktu Berangkat" wire:model.lazy="fdepartures" />
            <x-datetime-picker name="farrives" id="farrives" label="Waktu Pulang" placeholder="Waktu Pulang"
                wire:model.defer="farrives" :min="$fdepartures" />
            <x-select label="Anggota" placeholder="Pilih angget" multiselect :async-data="[
                    'api' => route('api.users.model'),
                    'method' => 'POST'
                ]" option-label="label" option-value="value" wire:model.defer="fmembers" />
        </div>

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
                <x-button primary label="Save" wire:click="addSppd({{ $i }})" x-on:click="close" />

                <div class="flex">
                    <x-button negative label="Batal" x-on:click="close" />
                </div>
            </div>
        </x-slot>
    </x-modal.card>
    @endif
</div>