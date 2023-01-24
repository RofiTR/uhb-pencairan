<div class="grid grid-cols-2 gap-3">
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
        <b>Riwayat transfer</b>
        <ul class="grid gap-3 mb-3">
            @if ($transactions)
            @foreach ($transactions as $tx)
            <li>
                {{ $tx->file->name }}
                <x-button xs label="Lihat" href="{{ asset('storage/'.$tx->file->file_path.'/'.$tx->file->file_name) }}"
                    target="_blank" />
            </li>
            @endforeach
            @endif
        </ul>
    </div>
</div>