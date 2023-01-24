<div class="grid @if(!request()->routeIs('proposal.show')) grid-rows-2 @endif grid-cols-2 grid-flow-col gap-3">
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
    @if (user()->hasRole('Keuangan') || user()->hasRole('Pimpinan'))
    <div class="row-span-2 bg-white shadow-sm sm:rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4">
            <x-input label="Nominal Pengajuan" wire:model="amount" x-mask:dynamic="$money($input, ',')" disabled />
            <x-select label="Verifikasi" :options="$statuses" option-label="label" option-value="value"
                wire:model.lazy="verificationStatus" disabled />
            <x-textarea label="Catatan" wire:model.lazy="notes" disabled />
            @if (user()->hasRole('Keuangan'))
            <x-input label="Kode Akun" wire:model="account" disabled />
            <x-select label="Pimpinan" placeholder="Pilih salah satu" :options="$approvers" option-label="label"
                option-value="value" wire:model.defer="approver" disabled />
            @endif
        </div>
    </div>
    @endif
    @if(user()->hasRole('Kasir'))
    <div class="row-span-2 bg-white shadow-sm sm:rounded-lg p-6">
        <div class="grid grid-cols-1 gap-4">
            <x-input label="Nomor Voucher" wire:model="voucher" disabled />
            <x-textarea label="Catatan" wire:model.lazy="notes" disabled />
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
    </div>
    @endif
</div>