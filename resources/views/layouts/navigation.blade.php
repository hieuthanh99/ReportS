<nav x-data="{ open: false }" class="bg-white border-b border-gray-200" style="padding: 10px; margin: 0">
    <div class="container mx-auto">
        <div class="flex items-center h-16 relative" style="z-index: 1000">
            <!-- Logo -->
            <nav>
                <ul>
                    <li style="cursor: pointer"><i class="fa-solid fa-bars" style="font-size: xx-large;"></i>
                        <ul class="menu-tree">
                            @if(Auth::user()->role !== 'staff' && Auth::user()->role !== 'sub_admin')
                                <li>
                                    <details close>
                                        <summary class="btn btn-primary">Quản lý danh mục</summary>
                                    <ul>
                                        <li>
                                            <details close>
                                                <summary>Văn bản</summary>
                                            <ul>
                                                <li><a href="{{route('documents.index')}}" title="">Danh mục văn bản</a></li>
                                                <li><a href="{{route('document_categories.index')}}" title="">Phân loại văn bản</a></li>
                                            </ul>
                                            </details>
                                        </li>
                                        <li>
                                            <details close>
                                                <summary>Cơ quan,tổ chức</summary>
                                            <ul>
                                                <li><a href="{{route('organization_types.index')}}" title="">Phân loại cơ quan, tổ chức</a></li>
                                                <li><a href="{{route('organizations.index')}}" title="">Danh mục cơ quan, tổ chức</a></li>
                                            </ul>
                                            </details>
                                        </li>
                                       
                                        <li>
                                            <details close>
                                                <summary>Người dùng</summary>
                                            <ul>
                                                <li><a href="{{route('positions.index')}}" title="">Danh mục chức vụ</a></li>
                                                <li><a href="{{route('users.index')}}" title="">Danh mục người dùng</a></li>
                                            </ul>
                                            </details>
                                        </li>
                                    </ul>
                                    </details>
                                </li>
                                <li>
                                    <details close>
                                        <summary>Quản lý công việc</summary>
                                <ul>
                                    <li><a href="{{route('task_groups.index')}}" title="">Nhóm nhiệm vụ</a></li>
                                    <li><a href="{{ route('tasks.byType', 'task') }}" title="">Nhiệm vụ</a></li>
                                    <li><a href="{{route('indicator_groups.index')}}" title="">Nhóm chỉ tiêu</a></li>
                                    <li><a href="{{ route('tasks.byType', 'target') }}" title="">Chỉ tiêu</a></li>
                                </ul>
                                    </details>
                            </li>
                            @endif
                                <li>
                                    <details close>
                                        <summary>Tổng hợp, báo cáo</summary>
                                <ul>
                                    @if(Auth::user()->role !== 'staff' && Auth::user()->role !== 'sub_admin')

                                    <li><a href="#" title="">Tổng hợp, thống kê</a>
                                        <ul>
                                            <li><a href="{{route('reports.withDocument')}}" title="">Báo cáo tổng hợp theo văn bản</a></li>
                                            <li><a href="{{route('reports.withUnit')}}" title="">Báo cáo tổng hợp theo đơn vị</a></li>
                                            <li><a href="{{route('reports.withPeriod')}}" title="">Báo cáo tổng hợp theo chu kỳ</a></li>
                                            <li><a href="{{route('reports.withDetails')}}" title="">Báo cáo chi tiết nhiệm vụ/chỉ tiêu</a></li>
                                        </ul>
                                    </li>
                                    @endif
                                    <li><a href="{{route('documents.report')}}" title="">Phê duyệt kết quả nhiệm vụ</a></li>
                                    <li><a href="{{route('documents.report.target')}}" title="">Phê duyệt kết quả chỉ tiêu</a></li>
                                </ul>
                                    </details>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="flex-shrink-0 px-4" style="margin-top: -8px">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo/logo2.png') }}" alt="Logo" class="block w-auto" style="width: 70px;">
                </a>
            </div>
            <div class="flex-shrink-0 px-4 header-title">
                <h3>Nền tảng quản lý nhiệm vụ Ủy ban quốc gia về chuyển đổi số</h3>
            </div>
            <div class="search-container px-4">
                <i class="fa-solid fa-magnifying-glass search-icon" onclick="toggleSearch()" style="cursor: pointer"></i>
            </div>
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
<style>
    #searchInput {
        width: 400px;
        padding: 10px; 
        border: 1px solid #ddd;
        border-radius: 5px; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        transition: all 0.3s ease; 
    }

    #searchInput::placeholder {
        color: #888; 
        font-style: italic; 
    }

    #searchInput:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
    }

    .menu-text{
        color: #000;
    }

    .menu-text:hover{
        font-size: 20px;
        font-weight: bold;
    }

    details summary {
        list-style: none;
        cursor: pointer;
    }

    details summary::before {
        content: '\23F5'; /* Mũi tên phải (icon khi đóng) */
    }

    details[open] summary::before {
        content: '\23F7'; /* Mũi tên xuống (icon khi mở) */
    }
</style>
<script>

    function toggleSearch() {
        const searchBox = document.querySelector('.search-box');
        searchBox.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const searchInField = document.getElementById('searchIn');
        const searchType = document.getElementById('searchType');
        
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }
        const currentUrl = window.location.pathname;
        console.log(currentUrl);

        const searchInParam = getQueryParam('search_in');

        const pathSegments = currentUrl.split('/');

        const firstSegment = pathSegments[1] || 'general'; // Phần đầu tiên, hoặc 'general' nếu không có
        const lastSegment = pathSegments[pathSegments.length - 1] || 'general'; // Phần cuối cùng, hoặc 'general' nếu không có
        console.log(firstSegment);
        console.log(pathSegments.length == 2);
        console.log(pathSegments[0]== '');
        if (pathSegments.length == 2 && pathSegments[0] == '' && pathSegments[1] == '') {
            searchInField.value = 'dashboard';
        }
        else if (firstSegment.includes('tasks')) {
            searchInField.value = firstSegment;
            searchType.value = lastSegment;
        }
        else if (searchInParam) {
            searchInField.value = searchInParam;
        }
        else if (currentUrl.includes('documents')) {
            searchInField.value = 'documents';
        } else if (currentUrl.includes('document_categories')) {
            searchInField.value = 'document_categories';
        } else if (currentUrl.includes('tasks')) {
            searchInField.value = 'tasks';
        } else if (currentUrl.includes('categories')) {
            searchInField.value = 'categories';
        } else if (currentUrl.includes('organization_types')) {
            searchInField.value = 'organization_types';
        } else if (currentUrl.includes('organizations')) {
            searchInField.value = 'organizations';
        } else if (currentUrl.includes('task_groups')) {
            searchInField.value = 'task_groups';
        } else if (currentUrl.includes('indicator_groups')) {
            searchInField.value = 'indicator_groups';
        } else if (currentUrl.includes('positions')) {
            searchInField.value = 'positions';
        } else if (currentUrl.includes('positions')) {
            searchInField.value = 'positions';
        } else if (currentUrl.includes('users')) {
            searchInField.value = 'users';
        }else if (firstSegment === 'report') {
            searchInField.value = 'report';
        }else if (firstSegment =='reports-with-unit') {
            searchInField.value = 'reports-with-unit';
        }else if (firstSegment == 'reports-with-period') {
            searchInField.value = 'reports-with-period';
        }else if (firstSegment == 'reports-with-details') {
            searchInField.value = 'reports-with-details';
        } else {
            searchInField.value = 'general';
        }
    });

</script>
<div class="rounded-lg search-box hidden text-center" style="margin: 10px auto">
    <form id="searchForm" action="{{ route('search') }}" method="GET">
        <input type="hidden" name="search_in" id="searchIn" value="">
        <input type="hidden" name="search_type" id="searchType" value="">

        <input type="text" placeholder="Tìm kiếm..." id="searchInput" name="query" value="{{ old('query') }}">
        <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Tìm kiếm</button>
    </form>
</div>