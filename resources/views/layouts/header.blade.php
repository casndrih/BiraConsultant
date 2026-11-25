<!-- Header -->
<header class="qj-toolbar text-gray-900 dark:text-white">
    <div class="qj-toolbar__inner">
            <!-- Left: Drawer trigger followed by brand -->
            <div class="qj-toolbar__main">
                <button type="button"
                        class="qj-drawer-trigger"
                        data-drawer-target="qj-navigation-drawer"
                        data-drawer-show="qj-navigation-drawer"
                        aria-controls="qj-navigation-drawer"
                        aria-label="Open navigation menu">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <a href="{{ route('home') }}" class="qj-brand" aria-label="BiraConsultant home">
                    <span class="qj-brand__mark">
                        <img src="{{ URL::asset('images/favicon/logo.svg') }}" alt="BiraConsultant logo">
                    </span>
                    <span class="qj-brand__name">BiraConsultant</span>
                </a>

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
    <!-- Flowbite off-canvas navigation drawer -->
    <aside id="qj-navigation-drawer"
           class="qj-navigation-drawer fixed left-0 top-0 z-50 h-screen w-80 max-w-[85vw] -translate-x-full overflow-y-auto border-r border-gray-200 bg-white p-5 shadow-xl transition-transform duration-300 ease-in-out dark:border-gray-700 dark:bg-gray-900"
           tabindex="-1"
           aria-labelledby="qj-navigation-drawer-title"
           aria-hidden="true">
        <div class="qj-navigation-drawer__header">
            <a href="{{ route('home') }}" class="qj-brand" aria-label="BiraConsultant home">
                <span class="qj-brand__mark">
                    <img src="{{ URL::asset('images/favicon/logo.svg') }}" alt="" aria-hidden="true">
                </span>
                <span class="qj-brand__name" id="qj-navigation-drawer-title">BiraConsultant</span>
            </a>
            <button type="button"
                    class="qj-navigation-drawer__close"
                    data-drawer-hide="qj-navigation-drawer"
                    aria-controls="qj-navigation-drawer"
                    aria-label="Close navigation menu">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <p class="qj-navigation-drawer__eyebrow">Explore</p>
        <nav class="qj-navigation-drawer__nav" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="qj-drawer-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('jobs') }}" class="qj-drawer-link {{ request()->routeIs('jobs', 'job.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="7" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                <span>Jobs</span>
            </a>
            <a href="{{ route('companies') }}" class="qj-drawer-link {{ request()->routeIs('companies', 'company.detail') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V5l7-2v18M19 21V9l-7-2M8 8v.01M8 12v.01M8 16v.01M15 12v.01M15 16v.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>Companies</span>
            </a>
            <a href="{{ route('blog') }}" class="qj-drawer-link {{ request()->routeIs('blog', 'blog.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 3h11l3 3v15H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 10h8M8 14h8M8 18h5M16 3v4h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                <span>Blog</span>
            </a>
            <a href="{{ route('contact') }}" class="qj-drawer-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v14H4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                <span>Contact</span>
            </a>
        </nav>

        @guest
            <div class="qj-navigation-drawer__footer">
                <a href="{{ route('login') }}" class="qj-drawer-secondary">Sign In</a>
                <a href="{{ route('register') }}" class="qj-drawer-primary">Create Account</a>
            </div>
        @else
            <div class="qj-navigation-drawer__footer">
                @if (Auth::user()->role == 1)
                    <a href="{{ route('admin-dashboard') }}" class="qj-drawer-link">Admin Dashboard</a>
                @elseif (Auth::user()->role == 2)
                    <a href="{{ route('company.dashboard') }}" class="qj-drawer-link">Company Dashboard</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="qj-drawer-link">My Dashboard</a>
                @endif
            </div>
        @endguest
    </aside>

