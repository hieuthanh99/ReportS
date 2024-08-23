<nav x-data="{ open: false }" class="bg-white border-b border-gray-200" style="padding: 10px">
    <div class="container mx-auto px-4">
        <div class="flex items-center h-16 relative" style="z-index: 1000">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo/image.png') }}" alt="Logo" class="block w-auto" style="width: 70px;">
                </a>
            </div>

            <nav>
                <ul>
                
                    @if(Auth::user()->role !== 'staff' && Auth::user()->role !== 'sub_admin')
                    <li><a href="#" title="Khóa học chuyên đề">Quản lý danh mục</a>
                        <!-- menu con sổ xuống cấp 1 -->
                        <ul>
                            <li><a href="#" title="">Văn bản</a>
                                <ul>
                                    <li><a href="{{route('documents.index')}}" title="">Danh mục văn bản</a></li>
                                    <li><a href="{{route('document_categories.index')}}" title="">Phân loại văn bản</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">Cơ quan,tổ chức</a>
                                <ul>
                                    <li><a href="{{route('organization_types.index')}}" title="">Phân loại cơ quan, tổ chức</a></li>
                                    <li><a href="{{route('organizations.index')}}" title="">Danh mục cơ quan, tổ chức</a></li>
                                </ul>
                            </li>
                            <li><a href="{{route('task_groups.index')}}" title="">Nhóm nhiệm vụ</a></li>
                            <li><a href="{{route('indicator_groups.index')}}" title="">Nhóm chỉ tiêu</a></li>
                            <li><a href="#" title="">Người dùng</a>
                                <ul>
                                    <li><a href="{{route('positions.index')}}" title="">Danh mục Chức vụ</a></li>
                                    <li><a href="{{route('users.index')}}" title="">Danh mục người dùng</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    
                    <li><a href="#" title="Quản lý công việc">Quản lý công việc</a>
                        <ul>
                            <li><a href="{{ route('tasks.byType', 'task') }}" title="">Nhiệm vụ</a></li>
                            <li><a href="{{ route('tasks.byType', 'target') }}" title="">Chỉ tiêu</a></li>
                   
                        </ul>
                    </li>
                    @endif
                    <li><a href="#" title="Liện hệ">Tổng hợp, báo cáo</a>
                        <ul>
                            <li><a href="#" title="">Tổng hợp, thống kê</a></li>
                            <li><a href="{{route('documents.report')}}" title="">Báo cáo</a></li>
                        </ul>
                    </li>
                
                </ul>
            </nav>
            <!-- Navigation Links -->
            {{-- <div class="flex space-x-8 px-4 relative">
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supper_staff')
                    <div class="relative group">
                        <x-nav-link href="#" class="flex items-center space-x-1 text-lg font-semibold text-black hover:text-blue-400">
                            <span>{{ __('Quản Lý Danh Mục') }}</span>
                            <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </x-nav-link>
                        <div class="dropdown-menu absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md">
                            <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index')" class="block px-4 py-2 text-black hover:bg-gray-200 text-lg font-semibold">
                                {{ __('Văn Bản') }}
                            </x-nav-link>
                            <x-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.index')" class="block px-4 py-2 text-black hover:bg-gray-200 text-lg font-semibold">
                                {{ __('Cơ Quan/Tổ Chức') }}
                            </x-nav-link>
                            <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" class="block px-4 py-2 text-black hover:bg-gray-200 text-lg font-semibold">
                                {{ __('Phân Loại Nhiệm Vụ') }}
                            </x-nav-link>
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="block px-4 py-2 text-black hover:bg-gray-200 text-lg font-semibold">
                                {{ __('Người Dùng') }}
                            </x-nav-link>
                        </div>
                    </div>
                @endif
                <div class="relative group">
                    
                    <x-nav-link href="#" class="flex items-center space-x-1 text-lg font-semibold text-black hover:text-blue-400">
                        <span>{{ __('Quản Lý Công Việc') }}</span>
                        <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </x-nav-link>
                
                  
                    <div class="dropdown-menu absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md">
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supper_staff')
                        <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.index')" class="block px-4 py-2 text-black hover:bg-gray-200 text-lg font-semibold">
                            {{ __('Nhiệm Vụ/Chỉ Tiêu') }}
                        </x-nav-link>
                        @endif
                        <x-nav-link :href="route('documents.report')" :active="request()->routeIs('users.index')" class="block px-4 py-2 text-black hover:bg-gray-200 text-lg font-semibold">
                            {{ __('Báo cáo thống kê') }}
                        </x-nav-link>
                    </div>
                </div>
            </div> --}}

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-auto" style="margin-left: auto;">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
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
                        {{-- <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link> --}}

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Đăng xuất') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-black">
                {{ __('Trang chủ') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index')" class="text-black">
                {{ __('Danh mục văn bản') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" class="text-black">
                    {{ __('Danh mục phân loại') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="text-black">
                    {{ __('Người dùng') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-black">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();" class="text-black">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
