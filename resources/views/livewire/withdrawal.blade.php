<div class="flex flex-row flex-wrap items-start">
    <div class="w-1/2 px-2 grid gap-3">
        <div class=" bg-white shadow-sm sm:rounded-lg p-6">
            <b>Detail pengajuan</b>
            <div class="grid grid-cols-1 gap-4">
                <ul>
                    <li>Nama kegiatan: {{ $proposal->name }}</li>
                    <li>Uraian kegiatan: {{ $proposal->description }}</li>
                    <li>Nominal pengajuan: Rp {{ number_format($proposal->amount, 2, ',', '.') }}</li>
                </ul>
            </div>
            <b class="mt-4">Lampiran</b>
            <ul>
                @foreach ($proposal->files as $file)
                <li>
                    {{ $file->name }}
                    <x-button xs label="Lihat" href="{{ asset('storage/'.$file->file_path.'/'.$file->file_name) }}"
                        target="_blank" />
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <b>Riwayat pengajuan</b>
            <div class="grid grid-cols-1 gap-4">
                <ul>
                    @foreach ($proposal->histories as $history)
                    <li>{{ $history->status->name }}
                        {{ $history->created_at }}
                        @if($history->notes)
                        <br><i>catatan: {{ $history->notes }}</i>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="w-1/2 px-2">
        <div class="row-span-2 bg-white shadow-sm sm:rounded-lg p-6">
            <div class="grid grid-cols-1 gap-4">
                @if ($proposal->histories->last()->status->value >= 6)
                <x-input label="Nomor Voucher" wire:model="voucher" readonly />
                <x-textarea label="Catatan" wire:model.lazy="notes" readonly />
                @else
                <x-input label="Nomor Voucher" wire:model="voucher" />
                <x-textarea label="Catatan" wire:model.lazy="notes" />
                <x-button primary label="Simpan" wire:click="save" />
                @endif
            </div>
        </div>
    </div>
</div>