@can('user.edit')
<x-button.circle xs warning icon="pencil" href="{{ route('support.master.user.show', ['user'=>$row->id]) }}" />
@endcan
@can('user.delete')
<x-button.circle xs negative icon="trash" x-data="{ tooltip: 'Hapus' }" x-tooltip="tooltip"
  wire:click="$set('user','{{ $row->id }}');" onclick="$openModal('deleteModal')" />
@endcan