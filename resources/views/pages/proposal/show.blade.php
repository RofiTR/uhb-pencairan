<x-app-layout>
  <x-slot name="page_title">
    {{ $page_title }}
  </x-slot>

  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ $page_title }}
    </h2>
  </x-slot>


  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-3">
      <livewire:proposal.detail :proposal="$proposal" />
      @if ($proposal->histories->last()->status->value >= 6)
      <livewire:proposal.lpj :proposal="$proposal" />
      @endif
    </div>
  </div>
</x-app-layout>