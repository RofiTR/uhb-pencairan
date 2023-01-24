@if($menu->permission == NULL || user()->hasRole('SA') || user()->hasAnyPermission(str_replace('.', ' ', $menu->permission)))
@if (!$menu->parent)
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
@if(count($menu->children) == 0)
<li>
  <x-nav-link href="{{ url($menu->url) }}" :active="request()->is($menu->url)">
    {{ ucwords($menu->label) }}
  </x-nav-link>
</li>
@elseif(count($menu->children) > 0)
<li class="group">
  <x-nav-link class="flex items-center" :active="request()->routeIs($menu->route)">
    <span class="pr-1 flex-1">{{ ucwords($menu->label) }}</span>
    <span>
      <svg class="fill-current h-4 w-4 transform group-hover:-rotate-180 transition duration-150 ease-in-out z-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
      </svg>
    </span>
  </x-nav-link>
  <ul class="bg-white rounded-lg transform scale-0 p-2 shadow-lg group-hover:scale-100 absolute 
  transition duration-150 ease-in-out origin-top min-w-32 dark:bg-slate-800 dark:border dark:border-slate-700">
    @each('partials.navigation-item', $menu->children, 'menu', 'empty')
  </ul>
</li>
@endif
</div>
@else
@if(count($menu->children) == 0)
<li class="">
  <x-nav-link href="{{ url($menu->url) }}" :active="request()->is($menu->url)">
    {{ ucwords($menu->label) }}
  </x-nav-link>
</li>
@else
<li class="rounded-lg relative ">
  <x-nav-link class="w-full text-left flex items-center outline-none focus:outline-none">
    <span class="pr-1 flex-1">{{ ucwords($menu->label) }}</span>
    <span class="mr-auto">
      <svg class="fill-current h-4 w-4 transition duration-150 ease-in-out" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
      </svg>
    </span>
  </x-nav-link>
  <ul class="bg-white rounded-lg absolute top-0 p-2 shadow-lg right-0 transition duration-150 ease-in-out origin-top-left min-w-32 dark:bg-slate-800 dark:border dark:border-slate-700
  ">
    @each('partials.navigation-item', $menu->children, 'menu', 'empty')
  </ul>
</li>
@endif
@endif
@endif