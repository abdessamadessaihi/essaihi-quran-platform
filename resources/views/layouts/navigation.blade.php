<nav x-data="{ open: false }" class="bg-white dark:bg-emerald-950 border-b border-emerald-100 dark:border-emerald-900/60 shadow-sm transition-colors duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transition-transform hover:scale-105">
                        <!-- يمكنك استبدال هذا برمز المصحف أو الشعار المخصص -->
                        <x-application-logo class="block h-10 w-auto fill-current text-emerald-900 dark:text-amber-400" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex h-full">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                class="font-arabic font-medium text-base text-emerald-900 dark:text-emerald-200 active:text-amber-500">
                        ✨ {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="left" width="48"> <!-- تم تغيير المحاذاة إلى اليسار لتناسب الـ RTL العربي -->
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-emerald-100 dark:border-emerald-800 text-sm leading-4 font-medium rounded-xl text-emerald-800 dark:text-emerald-200 bg-emerald-50/50 dark:bg-emerald-900/30 hover:bg-emerald-50 dark:hover:bg-emerald-900/50 focus:outline-none transition ease-in-out duration-150 gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <div class="font-arabic font-semibold">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-emerald-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- تم تعطيل رابط الـ Profile لمنع الخطأ لأن الراوت غير موجود في ملفك -->
                        @if(Route::has('profile.edit'))
                            <x-dropdown-link :href="route('profile.edit')" class="font-arabic text-right">
                                👤 {{ __('Profile') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    class="font-arabic text-right text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                🚪 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-emerald-50 dark:border-emerald-900">
        <div class="pt-2 pb-3 space-y-1 bg-emerald-50/30 dark:bg-emerald-950/20">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-arabic text-right">
                🕌 {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-emerald-100 dark:border-emerald-900 bg-white dark:bg-emerald-950">
            <div class="px-4">
                <div class="font-arabic font-bold text-base text-emerald-950 dark:text-emerald-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-emerald-600 dark:text-emerald-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- حماية برمجية لرابط الجوال أيضاً -->
                @if(Route::has('profile.edit'))
                    <x-responsive-nav-link :href="route('profile.edit')" class="font-arabic text-right">
                        👤 {{ __('Profile') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            class="font-arabic text-right text-red-600 dark:text-red-400"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>