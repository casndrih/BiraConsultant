<header>
    <nav class="qj-admin-topbar px-4 lg:px-6 py-2.5">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <button id="toggleNavigation" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation"
                    aria-controls="drawer-navigation"
                    class="hidden p-2 mr-3 text-black dark:text-white rounded cursor-pointer lg:inline hover:text-yellow-300 dark:text-gray-400 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M3,18h18v-2L3,16v2zM3,13h18v-2L3,11v2zM3,6v2h18L21,6L3,6z" />
                    </svg>
                </button>

                <button data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation"
                    aria-controls="drawer-navigation"
                    class="p-2 mr-2 text-black dark:text-white rounded-lg cursor-pointer lg:hidden hover:text-yellow-300">
                    <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M3,18h18v-2L3,16v2zM3,13h18v-2L3,11v2zM3,6v2h18L21,6L3,6z" />
                    </svg>
                    <span class="sr-only">Toggle sidebar</span>
                </button>

                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ URL::asset('images/favicon/logo.svg') }}" class="h-9 mr-1" alt="BiraConsultant Logo" />
                    <span class="text-xl font-medium whitespace-nowrap text-black dark:text-white">Jobs Admin Portal</span>
                </a>
            </div>

            <!-- User profile image aligned to far right -->
            <div class="flex items-center ml-auto">
                <button type="button"
                    class="flex text-sm bg-gray-100 dark:bg-black rounded-full focus:ring-4 focus:ring-gray-300"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                    <span class="sr-only">Open user menu</span>
                    @if (Auth::check() && Auth::user()->role == 1 && Auth::user()->image)
                        <img class="w-8 h-8 object-cover rounded-full" src="{{ asset(Auth::user()->image) }}"
                            alt="{{ Auth::user()->name }} photo">
                    @else
                        <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gray-200 text-sm font-semibold text-gray-700 dark:bg-gray-700 dark:text-white" aria-hidden="true">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                    @endif
                </button>
            </div>

            <!-- Dropdown menu -->
            <div class="hidden z-50 my-4 w-35 text-base list-none bg-white dark:bg-custom-dark rounded divide-y divide-gray-100 shadow"
                id="dropdown">
                <ul class="py-1 font-light text-black dark:text-white" aria-labelledby="dropdown">
                    <li>
                        <a href="{{ URL::to('/signout') }}"
                            class="block py-2 px-4 text-md font-medium hover:bg-gray-100">Signout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
