@if(count($menu->children) == 0)
<x-responsive-nav-link href="{{ url($menu->url) }}" :active="request()->is($menu->url)" class="py-3 text-lg">
  {{ ucwords($menu->label) }}
</x-responsive-nav-link>
@else
<div class="flex flex-col md:flex-row" x-data="{ {{ $menu->name }}: false }">
  <x-responsive-nav-link class="py-3 text-lg text-gray-700 w-full" x-on:click="{{ $menu->name }} = ! {{ $menu->name }}" :active="request()->routeIs($menu->route)">
    {{ ucwords($menu->label) }}
    <div class="ml-1">
      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </div>
  </x-responsive-nav-link>

  <div class="relative overflow-scroll" x-show="{{ $menu->name }}" x-collapse>
    <div class="text-gray-700 ml-2 py-2 space-y-3">
      <div class="flex flex-col">
        @each('partials.navigation-item-mobile', $menu->children, 'menu', 'empty')
      </div>
    </div>
  </div>
</div>
@endif