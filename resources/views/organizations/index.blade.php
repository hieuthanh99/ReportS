@extends('layouts.app')

@section('content')
    <style>
        .toggle-children {
            border: none;
            background: none;
            cursor: pointer;
        }

        .toggle-children i {
            transition: transform 0.3s;
        }

        .toggle-children i.fa-minus {
            transform: rotate(45deg);
        }

        .toggle-children i.fa-plus {
            transform: rotate(0deg);
        }

        /* Remove border inside child nodes */
        .tree ul {
            padding-left: 0;
        }

        .tree ul ul {
            padding-left: 1em;
        }

        .tree li {
            position: relative;
            margin-left: 1em;
            padding-left: 1em;
        }

        .tree li::before {
            content: '';
            position: absolute;
            top: 0;
            left: -1em;
            width: 1em;
            height: 1px;
            background-color: #ccc;
            /* Adjust color as needed */
            z-index: 1;
        }

        .tree li::after {
            content: '';
            position: absolute;
            top: 0;
            left: -1em;
            width: 1px;
            height: 100%;
            background-color: #ccc;
            /* Adjust color as needed */
            z-index: 0;
        }

        .tree li:last-child::after {
            height: 0;
        }

        /* Optional: style toggle button */
        .tree .toggle-children {
            background: none;
            border: none;
            cursor: pointer;
        }

        .tree .toggle-children::before {
            content: '+';
            display: inline-block;
            margin-right: 5px;
        }

        .tree ul.hidden {
            display: none;
        }

        #organization-details {
            border: 1px solid #e2e8f0;
            /* Light border color */
            border-radius: 8px;
            /* Rounded corners */
            background-color: #ffffff;
            /* White background */
            padding: 1.5rem;
            /* Padding */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            max-height: calc(100vh - 4rem);
            /* Max height for scrolling */
            overflow-y: auto;
            /* Scroll if content is too tall */
        }

        #organization-details h2 {
            margin-bottom: 1.5rem;
            /* Space below title */
            font-size: 1.5rem;
            /* Font size for title */
            color: #1a202c;
            /* Dark color for the title */
        }

        #organization-details p {
            margin-bottom: 0.5rem;
            /* Space below paragraphs */
        }

        #details-content {
            /* Additional styles for details content if needed */
        }
    </style>
    <div class="container mx-auto px-4 py-6 flex">
        <!-- Tree View -->
        <div class="w-1/2 pr-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-4xl font-bold text-gray-900">Danh sách danh mục</h1>
                <button onclick="showAddChildModal(null)"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">Thêm
                    mới</button>
            </div>

            @if (session('success'))
                <div id="success-message"
                    class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
                    {{ session('success') }}
                    <button id="close-message" class="absolute top-2 right-2 text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="overflow-x-auto bg-white p-6 border rounded-lg shadow-lg">
                @if ($tree->isEmpty())
                    <p class="text-gray-500">Chưa có danh mục nào. Hãy thêm danh mục mới.</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($tree as $node)
                            @include('organizations.partials.node', ['node' => $node])
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Details View -->
        <!-- Details View -->
        <div class="w-1/2 pl-4">
            <div class="bg-white p-6 border rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">Thông Tin Danh Mục</h2>
                <div id="organization-details">
                    <div id="details-content">
                        <p class="text-gray-500">Chọn một danh mục để xem chi tiết.</p>
                    </div>
                </div>
                <!-- User List -->
                <div class="mt-6">
                    <h3 class="text-xl font-semibold mb-2">Danh sách người dùng</h3>
                    <ul id="user-list" class="list-disc pl-5 text-gray-700">
                        <!-- User items will be populated here -->
                    </ul>
                </div>

                <!-- Assign User Button -->
                <div class="mt-6 flex items-center">
                    <button id="assign-user-button"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300"
                        onclick="showAssignUserModal()">
                        Gán Người Dùng
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal for Assigning User -->
        <div id="assignUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h2 class="text-xl font-bold mb-4">Gán Người Dùng</h2>
                <form id="assignUserForm">
                    @csrf
                    <input type="hidden" name="organization_id" id="organization_id" value="">
                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-700">Chọn Người Dùng</label>
                        <select name="user_id" id="user_id" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <!-- User options will be populated here -->
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeAssignUserModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Hủy</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Gán</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Modal for Adding Child Node -->
    <div id="addChildModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-1/3">
            <h2 class="text-xl font-bold mb-4">Add Node</h2>
            <form id="addChildForm">
                @csrf
                <input type="hidden" name="parent_id" id="parent_id" value="">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>
                <div class="mb-4">
                    <label for="code" class="block text-gray-700">Code</label>
                    <input type="text" name="code" id="code" class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-gray-700">Type</label>
                    <select name="type" id="type" class="w-full border rounded-lg px-3 py-2 mt-1">
                        <option value="tỉnh">Tỉnh</option>
                        <option value="bộ">Bộ</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone" class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeAddChildModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddChildModal(parentId) {
            document.getElementById('parent_id').value = parentId;
            document.getElementById('addChildModal').classList.remove('hidden');
        }

        function closeAddChildModal() {
            document.getElementById('addChildModal').classList.add('hidden');
        }

        document.getElementById('addChildForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route('organizations.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to add node');
                    }
                });
        });

        function loadDetails(organizationId) {
            fetch(`/organizations/${organizationId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('organization-details').innerHTML = `
                    <h3 class="text-2xl font-bold">${data.name}</h3>
                    <p><strong>Code:</strong> ${data.code}</p>
                    <p><strong>Type:</strong> ${data.type}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Phone:</strong> ${data.phone}</p>
                    
                `;
                });
        }

        function toggleChildren(button) {
            const nextElement = button.parentElement.nextElementSibling;
            const icon = button.querySelector('i');

            if (nextElement.classList.contains('hidden')) {
                nextElement.classList.remove('hidden');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus'); // Thay đổi icon thành dấu trừ
            } else {
                nextElement.classList.add('hidden');
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus'); // Thay đổi icon thành dấu cộng
            }
        }

        function showAssignUserModal() {
            document.getElementById('assignUserModal').classList.remove('hidden');
            populateUserOptions();
        }

        function closeAssignUserModal() {
            document.getElementById('assignUserModal').classList.add('hidden');
        }

        document.getElementById('assignUserForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route('users.assign') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to assign user');
                    }
                });
        });

        function populateUserOptions() {
            console.log("data");
            fetch('{{ route('users.listAssign') }}')
                .then(response => response.json())
                .then(data => {
                    const userSelect = document.getElementById('user_id');
                    userSelect.innerHTML = '<option value="">Chọn người dùng</option>';
                    data.users.forEach(user => {
                        userSelect.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                    });
                });
        }
    </script>


@endsection
