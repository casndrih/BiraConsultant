<!-- Header -->
<header class="qj-toolbar text-gray-900 dark:text-white">
    <div class="qj-toolbar__inner">
            <!-- Left: Logo & Nav -->
            <div class="qj-toolbar__main">
                <a href="{{ route('home') }}" class="qj-brand" aria-label="QJobs home">
                    <span class="qj-brand__mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M19.8 4.2C12.7 4.2 7.2 6.2 5.1 10.2c-1.8 3.4.3 7.1 3.9 7.1 4.8 0 8.7-5.2 10.8-13.1Z" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3.9 20c2.1-4 5.4-6.8 9.8-8.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="qj-brand__name">QJobs</span>
                </a>
                <nav class="qj-toolbar__nav hidden md:flex" aria-label="Main navigation">
                    <a href="{{ route('jobs') }}" class="qj-nav-link {{ request()->routeIs('jobs', 'job.*') ? 'is-active' : '' }}">Jobs</a>
                    <a href="{{ route('companies') }}" class="qj-nav-link {{ request()->routeIs('companies', 'company.detail') ? 'is-active' : '' }}">Companies</a>
                    <a href="{{ route('blog') }}" class="qj-nav-link {{ request()->routeIs('blog', 'blog.*') ? 'is-active' : '' }}">Blog</a>
                    <a href="{{ route('contact') }}" class="qj-nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
                </nav>
            </div>

            <!-- Right: Auth Button or User Image -->
            <div class="qj-toolbar__actions">
                @guest
                    <a data-modal-target="signin-modal" data-modal-show="signin-modal"
                       class="qj-text-action cursor-pointer">
                        Sign In
                    </a>
                    <a data-modal-target="signup-modal" data-modal-show="signup-modal"
                       class="qj-action-button qj-action-button--primary cursor-pointer">
                        Sign Up
                    </a>
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
