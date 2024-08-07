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
 <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg flex" style="margin-top: 10px;">
        @if (session('success'))
                <div id="success-message"
                    class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg relative">
                    {{ session('success') }}
                    <button id="close-message" class="absolute top-2 right-2 text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        <!-- Tree View -->
        <div class="w-1/2 pr-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-4xl font-bold text-gray-900"></h1>
                <button onclick="showAddChildModal(null)"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300"> <i class="fas fa-plus"></i></button>
            </div>

            

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
                    <label class="block text-gray-700 text-sm font-medium mb-2">Danh sách nhân viên</label>
                    <div class="overflow-x-auto">
                        <table id="task-table" class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="py-2 px-4 text-left text-gray-600">Mã nhân viên</th>
                                    <th class="py-2 px-4 text-left text-gray-600">Họ và tên</th>
                                    <th class="py-2 px-4 text-left text-gray-600">Email</th>
                                    <th class="py-2 px-4 text-left text-gray-600">Số điện thoại</th>
                                    <th class="py-2 px-4 text-left text-gray-600">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="assign-user-list">
                                <!-- Danh sách đầu việc sẽ được chèn vào đây -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="assign-user-button"
                        class="hidden bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300 mt-2">Gán
                        nhân viên</button>
                </div>

                {{-- <!-- Assign User Button -->
                <div class="mt-6 flex items-center">
                    <button id="assign-user-button"
                        class="hidden bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300"
                        onclick="showAssignUserModal()">
                        Gán Người Dùng
                    </button>
                </div> --}}
            </div>
        </div>
        <div id="assign-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
                <h2 class="text-xl font-bold mb-4">Danh sách nhân viên</h2>
                <div class="mb-4">
                    <label for="search-user" class="block text-gray-700 text-sm font-medium mb-2">Tìm kiếm</label>
                    <input type="text" id="search-user" class="form-input w-full border border-gray-300 rounded-lg p-2"
                        placeholder="Tìm kiếm tên">
                </div>
                <div class="mb-4 overflow-x-auto">
                    <table id="existing-user-table" class="w-full border border-gray-300 rounded-lg">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b checkbox-column">
                                    <input type="checkbox" id="check-all-user">
                                    <label for="check-all-user" class="text-gray-700 text-sm font-medium"></label>
                                </th>
                                <th class="py-2 px-4 border-b">Mã nhân viên</th>
                                <th class="py-2 px-4 border-b">Tên nhân viên</th>
                                <th class="py-2 px-4 border-b">Email nhân viên</th>
                                <th class="py-2 px-4 border-b">Số điện thoại</th>
                            </tr>
                        </thead>
                        <form id="assign-user-form" method="POST" action="{{ route('saveAssignedUsers') }}">
                            @csrf
                            <tbody id="existing-user" style="text-align: center">
                                <!-- Danh sách đầu việc sẽ được chèn vào đây -->
                            </tbody>
                        </form>

                    </table>
                </div>
                <button type="button" id="assign-user"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Gán
                </button>
                <button type="button" id="cancel-assign-user"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Hủy
                </button>
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
    // Clear the user list before fetching new data
    document.getElementById('assign-user-list').innerHTML = "";

    console.log("organizationId");
    console.log(organizationId);
    fetch(`/organizations/${organizationId}`)
        .then(response => response.json())
        .then(data => {
            // Clear the user list again before inserting new data (just in case)
            document.getElementById('assign-user-list').innerHTML = "";
            
            const organization = data.organization;
            const users = data.organization.users;
            document.getElementById('organization-details').innerHTML = `
                <h3 class="text-2xl font-bold">${organization.name}</h3>
                <p><strong>Mã phòng ban:</strong> ${organization.code}</p>
                <p><strong>Loại phòng ban:</strong> ${organization.type}</p>
                <p><strong>Email:</strong> ${organization.email}</p>
                <p><strong>Số điện thoại:</strong> ${organization.phone}</p>
            `;
            document.getElementById('organization_id').value = organization.id;

            users.forEach(user => {
                console.log(user);
                const taskHTML = `
                    <tr>
                        <td class="py-2 px-4 border-b">${user.code}</td>
                        <td class="py-2 px-4 border-b">${user.name}</td>
                        <td class="py-2 px-4 border-b">${user.email}</td>
                        <td class="py-2 px-4 border-b">${user.phone}</td>
                        <td class="py-2 px-4 border-b" style="display: none">${user.id}</td>
                        <td class="py-2 px-4 border-b">
                            <form action="/users/${user.id}/destroyOrganization" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-blue-500 text-white px-2 py-1 rounded-lg shadow hover:bg-blue-600 transition duration-300"
                                    onclick="return confirm('Bạn có chắc chắn rằng muốn xóa nhân viên khỏi tổ chức này?');">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                document.getElementById('assign-user-list').insertAdjacentHTML('beforeend', taskHTML);
            });
            document.getElementById('assign-user-button').classList.remove('hidden');
        });
}


        function toggleChildren(button) {
            if (button != null) {
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
            console.log(this);
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

                    data.users.forEach(user => {
                        userSelect.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                    });
                });
        }


        let existingUserCodes = [];
        document.addEventListener('DOMContentLoaded', function() {
            function fetchUser(query = '') {
                fetch(`{{ route('users.search') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        const userTableBody = document.getElementById('existing-user');
                        userTableBody.innerHTML = ''; // Xóa nội dung hiện tại

                        // Kiểm tra xem có dữ liệu không
                        if (!data.user || data.user.length === 0) {
                            const row = document.createElement('tr');
                            const cell = document.createElement('td');
                            cell.colSpan = 4; // Chiếm toàn bộ số cột
                            cell.textContent = 'Không có nhân viên nào phù hợp với từ tìm kiếm';
                            row.appendChild(cell);
                            userTableBody.appendChild(row);
                            return;
                        }

                        data.user.forEach(task => {
                            // Kiểm tra xem taskCode đã tồn tại trong existingTaskCodes chưa
                            if (existingUserCodes.includes(task.code)) {
                                return; // Bỏ qua nếu taskCode đã tồn tại
                            }

                            const row = document.createElement('tr');

                            // Checkbox
                            const checkboxCell = document.createElement('td');
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.id = `user-${task.code}`;
                            checkbox.value = task.code;
                            checkbox.classList.add('user-checkbox');
                            checkboxCell.appendChild(checkbox);

                            const idCell = document.createElement('td');
                            idCell.textContent = task.id;
                            idCell.classList.add('user-id-assign');
                            idCell.style.display = 'none';
                            // Mã công việc
                            const codeCell = document.createElement('td');
                            codeCell.textContent = task.code;
                            codeCell.classList.add('user-code-assign');

                            // Tên công việc
                            const nameCell = document.createElement('td');
                            nameCell.textContent = task.name;
                            nameCell.classList.add('user-name-assign');

                            // Kế hoạch
                            const emailCell = document.createElement('td');
                            emailCell.textContent = task.email;
                            emailCell.classList.add('user-email-assign');

                            const phoneCell = document.createElement('td');
                            phoneCell.textContent = task.phone;
                            phoneCell.classList.add('user-phone-assign');

                            const inputField = document.createElement('input');
                            inputField.textContent = task.id;
                            inputField.classList.add('user-list-id');
                            inputField.name = 'user[]';
                            inputField.value = task.id;
                            inputField.style.display = 'none';

                            const inputOrganizationField = document.createElement('input');
                            inputOrganizationField.textContent = document.getElementById(
                                'organization_id').value;
                            inputOrganizationField.classList.add('organization_id_assign');
                            inputOrganizationField.name = 'organization_id_assign';
                            inputOrganizationField.value = document.getElementById('organization_id')
                                .value;
                            inputOrganizationField.style.display = 'none';



                            row.appendChild(checkboxCell);
                            row.appendChild(idCell);
                            row.appendChild(codeCell);
                            row.appendChild(nameCell);
                            row.appendChild(emailCell);
                            row.appendChild(phoneCell);

                            row.appendChild(inputOrganizationField);
                            row.appendChild(inputField);
                            userTableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error fetching user:', error));
            }

            // Khi nhấn nút Gán, xử lý các đầu việc được chọn
            document.getElementById('assign-user').addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll(
                    '#existing-user input[type="checkbox"]:checked');
                const assignedUsers = [];
                selectedCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox

                    const userId = row.querySelector('.user-id-assign').textContent;
                    const userCode = row.querySelector('.user-code-assign').textContent;
                    const userName = row.querySelector('.user-name-assign').textContent;
                    const userEmail = row.querySelector('.user-email-assign').textContent;
                    const userPhone = row.querySelector('.user-phone-assign').textContent;
                    const userOrganization = row.querySelector('.organization_id_assign')
                        .textContent;
                    assignedUsers.push({
                        userId: userId.trim(),
                        userCode: userCode.trim(),
                        userName: userName.trim(),
                        userEmail: userEmail.trim(),
                        userPhone: userPhone.trim(),
                        userOrganization: userOrganization.trim()
                    });
                });

                fetch('/assign-users', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content') // CSRF token
                        },
                        body: JSON.stringify(assignedUsers)
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        // Xử lý phản hồi từ server
                        loadDetails(data.organization_id);
                        // Đóng modal
                        document.getElementById('assign-user-modal').classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                // Đóng modal
                document.getElementById('assign-user-modal').classList.add('hidden');
            });

            // Khi người dùng gõ vào ô tìm kiếm
            document.getElementById('search-user').addEventListener('input', function(event) {
                const query = event.target.value;
                fetchUser(query);
            });

            // Khi checkbox "Chọn tất cả" thay đổi trạng thái
            document.getElementById('check-all-user').addEventListener('change', function(event) {
                const checked = event.target.checked;
                document.querySelectorAll('#existing-user input.user-checkbox').forEach(checkbox => {
                    checkbox.checked = checked;
                });
            });

            document.getElementById('cancel-assign-user').addEventListener('click', function(event) {
                event.preventDefault();
                document.getElementById('assign-user-modal').classList.add('hidden');
            });
            document.getElementById('assign-user-list').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-user')) {
                    const row = e.target.closest('tr');
                    const userCode = row.cells[1].innerText.trim();

                    // Xóa mã công việc khỏi mảng
                    existingUserCodes = existingUserCodes.filter(code => code !== userCode);

                    // Xóa hàng công việc khỏi bảng
                    row.remove();
                }
            });
            // Hiển thị popup chọn đầu việc có sẵn
            document.getElementById('assign-user-button').addEventListener('click', function() {
                document.getElementById('check-all-user').checked = false;
                fetchUser();
                document.getElementById('assign-user-modal').classList.remove('hidden');
            });

        });
    </script>


@endsection
