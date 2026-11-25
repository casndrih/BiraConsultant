<!-- Header -->
<header class="qj-toolbar text-gray-900 dark:text-white">
    <div class="qj-toolbar__inner">
            <!-- Left: Logo & Nav -->
            <div class="qj-toolbar__main">
                <a href="{{ route('home') }}" class="qj-brand" aria-label="BiraConsultant home">
                    <span class="qj-brand__mark">
                        <img src="{{ URL::asset('images/favicon/logo.svg') }}" alt="BiraConsultant logo">
                    </span>
                    <span class="qj-brand__name">BiraConsultant</span>
                </a>
                <nav class="qj-toolbar__nav hidden md:flex" aria-label="Main navigation">
                    <a href="{{ route('jobs') }}" class="qj-nav-link {{ request()->routeIs('jobs', 'job.*') ? 'is-active' : '' }}">Jobs</a>
                    <a href="{{ route('companies') }}" class="qj-nav-link {{ request()->routeIs('companies', 'company.detail') ? 'is-active' : '' }}">Companies</a>
                    <a href="{{ route('blog') }}" class="qj-nav-link {{ request()->routeIs('blog', 'blog.*') ? 'is-active' : '' }}">Blog</a>
                    <a href="{{ route('contact') }}" class="qj-nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
                </nav>
                <details class="qj-mobile-nav md:hidden">
                    <summary class="qj-mobile-nav__toggle" aria-label="Open main menu">
                        <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="sr-only">Menu</span>
                    </summary>
                    <nav class="qj-mobile-nav__panel" aria-label="Mobile navigation">
                        <a href="{{ route('jobs') }}" class="qj-mobile-nav__link {{ request()->routeIs('jobs', 'job.*') ? 'is-active' : '' }}">Jobs</a>
                        <a href="{{ route('companies') }}" class="qj-mobile-nav__link {{ request()->routeIs('companies', 'company.detail') ? 'is-active' : '' }}">Companies</a>
                        <a href="{{ route('blog') }}" class="qj-mobile-nav__link {{ request()->routeIs('blog', 'blog.*') ? 'is-active' : '' }}">Blog</a>
                        <a href="{{ route('contact') }}" class="qj-mobile-nav__link {{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
                    </nav>
                </details>
            </div>

            <!-- Right: Auth Button or User Image -->
            <div class="qj-toolbar__actions">
                @guest
                    <a href="{{ route('login') }}" class="qj-text-action">Sign In</a>
                    <a href="{{ route('register') }}" class="qj-action-button qj-action-button--primary">Sign Up</a>
                @else
                    @if (Auth::user()->role == 1)
                        <!-- Admin -->
                        <a href="{{ route('admin-dashboard') }}" class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-300 hidden sm:inline">Admin</span>
                            <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                 alt="Profile Picture" class="w-9 h-9 rounded-full object-cover">
                        </a>
                    @elseif (Auth::user()->role == 2)
                        <!-- Company -->
                        <div x-data="{ open: false }" @click.away="open = false" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2">
                                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                     alt="Profile Picture" class="w-9 h-9 rounded-full object-cover">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-custom-dark rounded-md shadow-lg z-50 ">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-black dark:text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('company.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
                                    <a href="{{ route('company.applications') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Applications</a>
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <a href="{{ URL::to('/signout') }}"
                                       class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">Sign Out</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Job Seeker / User -->
                        <div x-data="{ open: false }" @click.away="open = false" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2">
                                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                     alt="Profile Picture" class="w-9 h-9 rounded-full object-cover">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-custom-dark rounded-md shadow-lg z-50 ">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-black dark:text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('user.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
                                    <a href="{{ route('user.profile') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">My Profile</a>
                                    <a href="{{ route('user.applications') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">My Applications</a>
                                    @if(Auth::user()->company)
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <a href="{{ route('company.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-green-800 dark:text-green-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium">🏢 Company Dashboard</a>
                                    @endif
                                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <a href="{{ URL::to('/signout') }}"
                                       class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">Sign Out</a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endguest
            </div>
        </div>
</header>
