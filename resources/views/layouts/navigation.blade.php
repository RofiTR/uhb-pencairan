<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-100 dark:bg-slate-800 dark:border dark:border-slate-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard.index') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600 dark:text-slate-100" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <ul class="hidden space-x-8 items-center md:-my-px md:ml-10 md:flex">
                    @if (count($menuList) > 0)
                    @each('partials.navigation-item', $menuList, 'menu', 'empty')
                    @endif
                </ul>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ml-6 gap-3">
                <x-imui-dropdown align="right" width="w-[20rem]">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            @if (count($notifications) > 0)
                            <x-icon solid name="bell" class="w-5 h-5" />
                            @else
                            <x-icon name="bell" class="w-5 h-5" />
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @foreach ($notifications as $notification)
                        <x-nav-link href="{{ $notification->context . '/' . $notification->context_id }}">{{
                            str_replace('[nama]', $notification->causer->name, $notification->description) }}</x-nav-link>
                        @endforeach
                        <x-nav-link href="{{ route('notification.index') }}">Lihat semua notifikasi</x-nav-link>
                    </x-slot>
                </x-imui-dropdown>

                <x-imui-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('sso.destroy') }}">
                            @csrf

                            <x-imui-dropdown-link :href="route('sso.destroy')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-imui-dropdown-link>
                        </form>
                    </x-slot>
                </x-imui-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if (count($menuList) > 0)
            @each('partials.navigation-item-mobile', $menuList, 'menu', 'empty')
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('sso.destroy') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('sso.destroy')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>