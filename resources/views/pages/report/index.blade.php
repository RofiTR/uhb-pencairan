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
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <livewire:proposal.table page="report" category="NULL" />
        </div>
      </div>
    </div>
  </div>
</x-app-layout>