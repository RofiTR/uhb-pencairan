<div>
    @if ($tab == 'pk' || $tab == 'historypk')
    <x-button outline primary label="Persekot Kerja" wire:click="$set('tab', 'pk')" />
    <x-button primary label="Pencairan Mata Anggaran" wire:click="$set('tab', 'ma')" />
    @else
    <x-button primary label="Persekot Kerja" wire:click="$set('tab', 'pk')" />
    <x-button outline primary label="Pencairan Mata Anggaran" wire:click="$set('tab', 'ma')" />
    @endif
    <div>
        @if($tab == 'pk')
        <div class="my-4">
            Daftar pencairan Persekot Kerja <x-button flat wire:click="$set('tab', 'historypk')">Lihat riwayat pengajuan
            </x-button>
        </div>
        <livewire:proposal.table page="dashboard" category="PK" />
        @elseif ($tab == 'ma')
        <div class="my-4">
            Daftar pencairan Mata Anggaran <x-button flat wire:click="$set('tab', 'historyma')">Lihat riwayat pengajuan
            </x-button>
        </div>
        <livewire:proposal.table page="dashboard" category="MA" />
        @elseif($tab == 'historypk')
        <div class="my-4">
            Riwayat pencairan Persekot Kerja
        </div>
        <livewire:proposal.table page="history" category="PK" />
        @elseif($tab == 'historyma')
        <div class="my-4">
            Riwayat pencairan Mata Anggaran
        </div>
        <livewire:proposal.table page="history" category="MA" />
        @endif
    </div>
</div>