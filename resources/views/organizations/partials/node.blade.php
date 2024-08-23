<li class="mb-2">
    <div class="flex items-center">
        <button onclick="toggleChildren(this)" class="toggle-children text-gray-600 hover:text-gray-800 mr-2">
            <i class="fas fa-plus"></i> <!-- Icon + -->
        </button>
        <button onclick="loadDetails({{ $node['id'] }})" class="text-blue-600 hover:underline ml-2">
            {{ $node['name']}}
        </button>
        {{-- <span class="text-gray-800 ml-2">{{  }}</span> --}}

        <!-- Nút để thêm tổ chức con -->
        {{-- <button onclick="showAddChildModal({{ $node['id'] }})" class="ml-2 text-green-600 hover:text-green-800">
           Thêm mới
        </button> --}}
    </div>

    @if (!empty($node['children']))
        <ul class="ml-4 space-y-2 hidden">
            @foreach ($node['children'] as $child)
                @include('organizations.partials.node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
