@if ($this->page == 'withdrawal')
@if ($row->status == 4)
<x-button.circle xs info icon="check" href="{{ route('withdrawal.show', ['proposal'=>$row->id]) }}"
  x-data="{ tooltip: 'Pencairan' }" x-tooltip="tooltip" />
@endif
@if ($row->status == 6)
<x-button.circle xs emerald icon="printer" x-data="{ tooltip: 'Cetak' }" x-tooltip="tooltip"
  wire:click="$emitTo('proposal.upload-transaction', 'proposal', '{{ $row->id }}')"
  onclick="$openModal('uploadModal')" />
@if ($row->withdrawal['method'] == 'Transfer')
<x-button.circle xs red icon="newspaper" x-data="{ tooltip: 'Bukti transfer' }" x-tooltip="tooltip"
  wire:click="$emitTo('proposal.upload-transaction', 'proposal', '{{ $row->id }}')"
  onclick="$openModal('uploadModal')" />
@endif
@endif
@elseif($this->page == 'verification')
<x-button.circle xs info icon="check" href="{{ route('verification.show', ['type'=>'proposal','proposal'=>$row->id]) }}"
  x-data="{ tooltip: 'Verifikasi' }" x-tooltip="tooltip" />
@elseif($this->page == 'report')
<x-button.circle xs info icon="eye" href="{{ route('report.show', ['proposal'=>$row->id]) }}"
  x-data="{ tooltip: 'Lihat' }" x-tooltip="tooltip" />
@else
@if ($row->status == 6 && !$row->status_report)
<x-button.circle xs info icon="document" href="{{ route('proposal.show', ['proposal'=>$row->id]) }}"
  x-data="{ tooltip: 'Input LPJ' }" x-tooltip="tooltip" />
@else
<x-button.circle xs red icon="eye" href="{{ route('proposal.show', ['proposal'=>$row->id]) }}"
  x-data="{ tooltip: 'Lihat' }" x-tooltip="tooltip" />
@endif
@endif