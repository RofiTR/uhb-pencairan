@if (user()->hasRole('Keuangan'))
<x-button.circle xs negative icon="check"
  href="{{ route('verification.show', ['type'=>'lpj','proposal'=>$row->proposal_id]) }}"
  x-data="{ tooltip: 'Verifikasi' }" x-tooltip="tooltip" />
@endif
@if (user()->hasRole('Pimpinan') && $row->status == 2 && $row->sppd === 1)
<x-button.circle xs negative icon="check"
  href="{{ route('verification.show', ['type'=>'sppd','proposal'=>$row->proposal_id]) }}"
  x-data="{ tooltip: 'Verifikasi' }" x-tooltip="tooltip" />
@elseif (user()->hasRole('Pimpinan') && ($row->status == 2 || $row->status == 4))
<x-button.circle xs negative icon="check"
  href="{{ route('verification.show', ['type'=>'lpj','proposal'=>$row->proposal_id]) }}"
  x-data="{ tooltip: 'Verifikasi' }" x-tooltip="tooltip" />
@endif