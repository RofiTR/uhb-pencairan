<div>
    <div class="flex flex-row flex-wrap items-start">
        <div class="w-1/2 pr-2 grid gap-3">
            <div class=" bg-white shadow-sm sm:rounded-lg p-6">
                <b>Detail pengajuan</b>
                <ul>
                    <li>Nama kegiatan: {{ $proposal->name }}</li>
                    <li>Uraian kegiatan: {{ $proposal->description }}</li>
                    <li>Nominal pengajuan: Rp {{ number_format($proposal->amount, 2, ',', '.') }}</li>
                </ul>
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
            <div class=" bg-white shadow-sm sm:rounded-lg p-6">
                <b>Riwayat LPJ</b>
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
        </div>
        <div class="w-1/2 pl-2 grid gap-3">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form class="grid grid-cols-1 gap-4" wire:submit.prevent="save">
                    @if ($proposal->type->value == 1)
                    <div class="border rounded p-2">
                        SPPD
                        @foreach ($proposal->report->sppds as $sppd)
                        <ul class="border rounded p-2">
                            <li>Tujuan: {{ $sppd->destination }}</li>
                            <li>Waktu Berangkat: {{ $sppd->departure }}</li>
                            <li>Waktu Pulang: {{ $sppd->arrive }}</li>
                            <li>Anggota:
                                <ol>
                                    @foreach ($sppd->members as $member)
                                    <li>{{ $member->user->name }}</li>
                                    @endforeach
                                </ol>
                            </li>
                            <li>
                                <x-imui-button type="button" class="w-fit" wire:click="$emitTo('proposal.verification.sppd', 'setSppd', '{{ $sppd->id }}')" onclick="$openModal('rincianModal')">Rincian
                                    Biaya
                                </x-imui-button>
                            </li>
                        </ul>
                        @endforeach
                        <x-modal.card title="Rincian Biaya SPPD" align="center" blur fullscreen wire:model.defer="rincianModal">
                            <livewire:proposal.verification.sppd />
                        </x-modal.card>
                    </div>
                    @endif
                    @if ($disable === TRUE)
                    <x-input label="Nominal Pencairan" wire:model="amount" x-mask:dynamic="$money($input, ',')" readonly
                        disabled />
                    <x-input label="Realisasi" wire:model.lazy="realization" x-mask:dynamic="$money($input, ',')"
                        disabled />
                    <x-input label="Saldo" wire:model="saldo" readonly disabled />
                    <x-input label="Kode Akun" wire:model="account" disabled />
                    @if (user()->hasRole('Keuangan'))
                    <x-select label="Pimpinan" placeholder="Pilih salah satu" :options="$approvers" option-label="label"
                        option-value="value" wire:model.defer="approver" disabled />
                    @endif
                    <x-select label="Verifikasi" :options="$statuses" option-label="label" option-value="value"
                        wire:model.lazy="verificationStatus" disabled />
                    <x-textarea label="Catatan" wire:model.lazy="notes" disabled />
                    @else
                    <x-input label="Nominal Pencairan" wire:model="amount" x-mask:dynamic="$money($input, ',')"
                        readonly />
                    @if (user()->hasRole('Keuangan'))
                    <x-input label="Realisasi" wire:model.lazy="realization" x-mask:dynamic="$money($input, ',')" />
                    <x-input label="Saldo" wire:model="saldo" readonly />
                    <x-input label="Kode Akun" wire:model="account" />
                    <x-select label="Pimpinan" placeholder="Pilih salah satu" :options="$approvers" option-label="label"
                        option-value="value" wire:model.defer="approver" />
                    @else
                    <x-input label="Realisasi" wire:model.lazy="realization" x-mask:dynamic="$money($input, ',')"
                        readonly />
                    <x-input label="Saldo" wire:model="saldo" readonly readonly />
                    @endif
                    @if ($isSppd == TRUE)
                    <x-select label="Verifikasi SPPD" :options="$statuses" option-label="label" option-value="value"
                        wire:model.lazy="verificationStatus" />
                    @else
                    <x-select label="Verifikasi LPJ" :options="$statuses" option-label="label" option-value="value"
                        wire:model.lazy="verificationStatus" />
                    @endif
                    <x-textarea label="Catatan" wire:model.lazy="notes" />
                    <x-button primary label="Simpan" wire:click="verificate" />
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>