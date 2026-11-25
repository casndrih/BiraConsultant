<!-- Google Material Symbols used by the site navigation -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">

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
                    <span class="material-symbols-rounded qj-nav-material-icon" aria-hidden="true">menu</span>
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
                <span class="material-symbols-rounded qj-nav-material-icon" aria-hidden="true">close</span>
            </button>
        </div>

        <p class="qj-navigation-drawer__eyebrow">Explore</p>
        <nav class="qj-navigation-drawer__nav" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="qj-drawer-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                <span class="material-symbols-rounded qj-drawer-material-icon" aria-hidden="true">home</span>
                <span>Home</span>
            </a>
            <a href="{{ route('jobs') }}" class="qj-drawer-link {{ request()->routeIs('jobs', 'job.*') ? 'is-active' : '' }}">
                <span class="material-symbols-rounded qj-drawer-material-icon" aria-hidden="true">work</span>
                <span>Jobs</span>
            </a>
            <a href="{{ route('companies') }}" class="qj-drawer-link {{ request()->routeIs('companies', 'company.detail') ? 'is-active' : '' }}">
                <span class="material-symbols-rounded qj-drawer-material-icon" aria-hidden="true">business</span>
                <span>Companies</span>
            </a>
            <a href="{{ route('blog') }}" class="qj-drawer-link {{ request()->routeIs('blog', 'blog.*') ? 'is-active' : '' }}">
                <span class="material-symbols-rounded qj-drawer-material-icon" aria-hidden="true">article</span>
                <span>Blog</span>
            </a>
            <a href="{{ route('contact') }}" class="qj-drawer-link {{ request()->routeIs('contact') ? 'is-active' : '' }}">
                <span class="material-symbols-rounded qj-drawer-material-icon" aria-hidden="true">mail</span>
                <span>Contact</span>
            </a>
        </nav>

        @guest
            <section class="qj-drawer-guest">
                <div class="qj-drawer-guest__icon" aria-hidden="true">
                    <span class="material-symbols-rounded qj-drawer-material-icon" aria-hidden="true">account_circle</span>
                </div>
                <h2>Welcome to BiraConsultant</h2>
                <p>Sign in to manage your profile, applications and account.</p>
                <div class="qj-drawer-guest__actions">
                    <a href="{{ route('login') }}" class="qj-drawer-secondary">Sign In</a>
                    <a href="{{ route('register') }}" class="qj-drawer-primary">Create Account</a>
                </div>
            </section>
        @else
            @php
                $drawerUser = Auth::user();
                $drawerRole = (int) $drawerUser->role === 1 ? 'Administrator' : ((int) $drawerUser->role === 2 ? 'Company Account' : 'Job Seeker');
                $drawerAvatar = $drawerUser->image
                    ? asset('storage/' . $drawerUser->image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($drawerUser->name) . '&background=FACC15&color=24452D&bold=true&size=160';
            @endphp
            <section class="qj-drawer-profile" aria-label="Signed-in account">
                <div class="qj-drawer-profile__identity">
                    <img class="qj-drawer-profile__avatar" src="{{ $drawerAvatar }}" alt="{{ $drawerUser->name }} profile picture">
                    <div class="qj-drawer-profile__copy">
                        <h2 title="{{ $drawerUser->name }}">{{ $drawerUser->name }}</h2>
                        <p title="{{ $drawerUser->email }}">{{ $drawerUser->email }}</p>
                        <span class="qj-drawer-role">{{ $drawerRole }}</span>
                    </div>
                </div>
                <div class="qj-drawer-profile__meta">
                    <div>
                        <span class="qj-drawer-profile__meta-icon" aria-hidden="true">✓</span>
                        <span>Account</span>
                        <strong>Active</strong>
                    </div>
                    @if($drawerUser->created_at)
                        <div>
                            <span class="qj-drawer-profile__meta-icon" aria-hidden="true">◷</span>
                            <span>Member since</span>
                            <strong>{{ $drawerUser->created_at->format('M Y') }}</strong>
                        </div>
                    @endif
                </div>
            </section>

            <p class="qj-navigation-drawer__eyebrow">Your account</p>
            <nav class="qj-navigation-drawer__nav" aria-label="Account navigation">
                @if ((int) $drawerUser->role === 1)
                    <a href="{{ route('admin-dashboard') }}" class="qj-drawer-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="5" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="10" width="8" height="11" rx="1.5" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="1.5" stroke="currentColor" stroke-width="1.8"/></svg>
                        <span>Admin Dashboard</span>
                    </a>
                @elseif ((int) $drawerUser->role === 2)
                    <a href="{{ route('company.dashboard') }}" class="qj-drawer-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V5l7-2v18M19 21V9l-7-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Company Dashboard</span>
                    </a>
                    <a href="{{ route('company.applications') }}" class="qj-drawer-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="7" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.8"/></svg>
                        <span>Applications</span>
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="qj-drawer-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M7 15a5 5 0 0 1 10 0M12 11a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <span>My Dashboard</span>
                    </a>
                    <a href="{{ route('user.profile') }}" class="qj-drawer-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 21a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('user.applications') }}" class="qj-drawer-link">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3h8l4 4v14H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8"/><path d="M14 3v5h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <span>My Applications</span>
                    </a>
                    @if($drawerUser->company)
                        <a href="{{ route('company.dashboard') }}" class="qj-drawer-link">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V5l7-2v18M19 21V9l-7-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Company Dashboard</span>
                        </a>
                    @endif
                @endif
            </nav>
            <div class="qj-navigation-drawer__footer qj-navigation-drawer__footer--account">
                @if ((int) $drawerUser->role === 1)
                    <a href="{{ route('admin-dashboard') }}" class="qj-drawer-primary">Open Admin Dashboard</a>
                @elseif ((int) $drawerUser->role === 2)
                    <a href="{{ route('company.dashboard') }}" class="qj-drawer-primary">Open Company Dashboard</a>
                @else
                    <a href="{{ route('user.profile') }}" class="qj-drawer-secondary">Edit My Profile</a>
                @endif
                <a href="{{ URL::to('/signout') }}" class="qj-drawer-signout">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Sign Out
                </a>
            </div>
        @endguest
    </aside>    </aside>

