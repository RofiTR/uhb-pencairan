<div>
    <x-button label="Pengajuan" wire:click="$set('tab', 'proposal')" />
    <x-button label="LPJ" wire:click="$set('tab', 'lpj')" />
    <div>
        @if($tab == 'proposal')
        <livewire:proposal.table page="verification" category="NULL" />
        @elseif ($tab == 'lpj')
        <livewire:proposal.report.table />
        @endif
    </div>
</div>