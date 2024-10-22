@extends('layouts.app')

@section('content')
    <style>
        .table-wrapper {
            overflow-y: auto;
            /* Cho phép cuộn dọc */
            max-height: 400px;
            /* Chiều cao tối đa của bảng */
        }

        #existing-organizations-table {
            border-collapse: collapse;
            /* Xóa bỏ khoảng cách giữa các cell */
            width: 100%;
            table-layout: fixed;
            /* Giúp đảm bảo bảng không bị co giãn khi cuộn */
        }

        thead {
            position: sticky;
            top: 0;
            /* Đặt tiêu đề cố định ở trên cùng */
            background-color: #ffffff;
            /* Màu nền của tiêu đề */
            z-index: 10;
            /* Đảm bảo tiêu đề nằm trên các hàng bên dưới */
        }

        th,
        td {
            padding: 8px;
            /* Khoảng cách trong các ô */
            border: 1px solid #ddd;
            /* Đường viền của ô */
        }
    </style>
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg mt-10">
        @if ($errors->any())
            <div class="error-message bg-yellow-300 text-white p-4 rounded-lg mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="error-message bg-yellow-300 text-white p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 mb-4">
            <!-- Phần chọn bộ lọc -->
            {{-- @php
        dd($taskTarget);
        @endphp --}}
            <div class="mb-6 flex flex-wrap gap-4 mb-4">
                <input type="hidden" id="task-target-id" name="task-target-id" value="{{ $taskTarget->id }}" />
                <input type="hidden" id="task-target-code" name="task-target-code" value="{{ $taskTarget->code }}" />
                <input type="hidden" id="type" name="task-target-code" value="{{ $taskTarget->type }}" />
                <div class="flex-1 min-w-[200px]">
                    <label for="organization_type_id" class="block text-gray-700">Loại cơ quan, tổ chức </label>
                    <select name="organization_type_id" id="organization_type_id"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                        <option value="">Chọn loại cơ quan</option>
                        @foreach ($organizationsType as $category)
                            <option value="{{ $category->id }}"
                                {{ old('organization_type_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->type_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4 overflow-x-auto table-wrapper" style="max-height: 500px; overflow-y: auto;">
                <table id="existing-organizations-table" class="w-full border border-gray-300 rounded-lg">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b checkbox-column">
                                <input type="checkbox" id="check-all-organizations">
                                <label for="check-all-organization" class="text-gray-700 text-sm font-medium"></label>
                            </th>
                            <th class="py-2 px-4 border-b">Mã Đầu việc</th>
                            <th class="py-2 px-4 border-b">Mã Cơ quan/tổ chức</th>
                            <th class="py-2 px-4 border-b">Tên Cơ quan/Tổ chức</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody id="existing-organizations" style="text-align: center">
                        <!-- Danh sách chỉ tiêu sẽ được chèn vào đây bằng JavaScript -->
                    </tbody>
                </table>
            </div>

            {{-- <div class="flex space-x-4">
                <p class="text-sm font-medium">
                    <span class="text-red-500">*</span> Nếu không chọn tổ chức nào, nhiệm vụ/chỉ tiêu sẽ giao cho người tạo.
                </p>
            </div> --}}
            <div class="mt-4 flex" style="justify-content: space-between">
                <button type="button" onclick="window.history.back();"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition duration-300">Quay
                    lại</button>

                <button type="button" id="assign-organizations-save"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">Giao
                    việc</button>
            </div>

            <div id="assigned-area" style="display: none">
                <div class="mb-4 overflow-x-auto table-wrapper" style="max-height: 500px; overflow-y: auto;">
                    <label for="assigned-organizations-table" class="block text-gray-700">Đã giao việc: </label>
                    <table id="assigned-organizations-table" class="w-full border border-gray-300 rounded-lg">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Mã Đầu việc</th>
                                <th class="py-2 px-4 border-b">Mã Cơ quan/tổ chức</th>
                                <th class="py-2 px-4 border-b">Tên Cơ quan/Tổ chức</th>
                                <th class="py-2 px-4 border-b">Email</th>
                                <th class="py-2 px-4 border-b">Số điện thoại</th>
                            </tr>
                        </thead>
                        <tbody id="assigned-organizations" style="text-align: center">
                            <!-- Danh sách chỉ tiêu sẽ được chèn vào đây bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="space-x-4 text-right">
                    <button onclick="goBackWithRefresh();" type="button"
                        class="btn bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition duration-300">Hoàn
                        thành</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function goBackWithRefresh() {
            var previousUrl = document.referrer;
            if (previousUrl) {
                window.location.href = previousUrl;
            } else {
                window.history.back();
            }
        }
        var assignedOrgLst = [];
        document.getElementById('organization_type_id').addEventListener('change', function() {
            var organizationTypeId = this.value;
            var taskTargetCode = document.getElementById('task-target-code').value;
            fetch(`/get-organization-id/${taskTargetCode}`)
                .then(response => response.json())
                .then(organizationId => {

                    // Sau khi nhận được organizationId, gửi yêu cầu để lấy danh sách organizations
                    return fetch(`/get-organizations/${organizationTypeId}`)
                        .then(response => response.json())
                        .then(data => {
                            fillData(data, organizationId); // Gọi fillData với organizationId
                        });
                })
                .catch(error => console.error('Error:', error));
        });
        // document.getElementById('back').addEventListener('click', function(event) {
        //     event.preventDefault();
        //     var type = document.getElementById('type');
        //     var selectedValue = type.value;
        //     // Chuyển hướng đến URL tương ứng với giá trị được chọn
        //     if (selectedValue) {
        //         window.location.href = `/tasks/type/${selectedValue}`;
        //     }
        // });

        function fillData(data, organization_id) {
            console.log("data fillData");
            console.log(data);
            console.log("data organization_id fillData");
            console.log(organization_id);
            const taskTargetId = document.getElementById('task-target-id').value;
            const taskTargetCode = document.getElementById('task-target-code').value;
            const tasksTableBody = document.getElementById('existing-organizations');
            tasksTableBody.innerHTML = ''; // Xóa nội dung hiện tại

            // Kiểm tra xem có dữ liệu không
            if (!data || data.length === 0) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 4; // Chiếm toàn bộ số cột
                cell.textContent = 'Không có đầu việc nào';
                row.appendChild(cell);
                tasksTableBody.appendChild(row);
                return;
            }
            const filteredData = data.filter(task =>
                !organization_id.some(id => id.organization_id === task.id)
            );
            filteredData.forEach(task => {
                const row = document.createElement('tr');

                // Checkbox
                const checkboxCell = document.createElement('td');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `organization-${taskTargetId}`;
                checkbox.value = taskTargetId;
                // organization_id.forEach(id => {
                //     if (task.id === id.organization_id) {
                //         checkbox.checked = true;
                //     }
                // })
                checkbox.classList.add('organization-checkbox');
                checkboxCell.appendChild(checkbox);

                // Tên công việc
                const taskCodeAssignCell = document.createElement('td');
                taskCodeAssignCell.textContent = taskTargetCode;
                taskCodeAssignCell.classList.add('task-code');


                const taskIdAssignCell = document.createElement('td');
                taskIdAssignCell.textContent = taskTargetId;
                taskIdAssignCell.classList.add('task-id');
                taskIdAssignCell.style.display = 'none';


                const idAssignCell = document.createElement('td');
                idAssignCell.textContent = task.id;
                idAssignCell.classList.add('id');
                idAssignCell.style.display = 'none';

                // Mã công việc
                const codeCell = document.createElement('td');
                codeCell.textContent = task.code;
                codeCell.classList.add('organization-code');

                // Tên công việc
                const nameCell = document.createElement('td');
                nameCell.textContent = task.name;
                nameCell.classList.add('organization-name');
                // Tên công việc
                const emailCell = document.createElement('td');
                emailCell.textContent = task.email;
                emailCell.classList.add('organization-email');

                // Tên công việc
                const phoneCell = document.createElement('td');
                phoneCell.textContent = task.phone;
                phoneCell.classList.add('organization-phone');

                row.appendChild(checkboxCell);
                row.appendChild(idAssignCell);
                row.appendChild(taskCodeAssignCell);
                row.appendChild(taskIdAssignCell);
                row.appendChild(codeCell);
                row.appendChild(nameCell);
                row.appendChild(emailCell);
                row.appendChild(phoneCell);


                tasksTableBody.appendChild(row);
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const taskTargetId = document.getElementById('task-target-id').value;
            const taskTargetCode = document.getElementById('task-target-code').value;
            fetchOrganizations(taskTargetId);

            function fetchOrganizations(id) {
                try {
                    const organizationsTotal = [];
                    fetch('/task-target/' + id + '/organizations', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                data.data.forEach(org => {

                                    organizationsTotal.push({
                                        code: org.code,
                                        name: org.name,
                                        email: org.email,
                                        phone: org.phone,
                                        task_code: taskTargetCode,
                                        task_id: id
                                    });
                                });
                                console.log("org");
                                console.log(organizationsTotal);
                                renderAssignTable(organizationsTotal);
                                document.getElementById('assigned-area').style.display = 'block';
                            } else {
                                console.log("dasadsadsadsata");
                                console.log(data.message); // In ra thông báo lỗi nếu có
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                        });

                } catch (error) {
                    console.error('Có lỗi xảy ra khi gọi API:', error);
                }
            }

            document.getElementById('assign-organizations-save').addEventListener('click', function() {

                const organizations = [];
                const selectedCheckboxes = document.querySelectorAll(
                    '#existing-organizations input[type="checkbox"]:checked');
                selectedCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr'); // Lấy hàng chứa checkbox
                    const organizationCode = row.querySelector('.organization-code').textContent;
                    const organizationName = row.querySelector('.organization-name').textContent;
                    const taskId = row.querySelector('.task-id').textContent;
                    const id = row.querySelector('.id').textContent;
                    const organizationEmail = row.querySelector('.organization-email').textContent;
                    const organizationPhone = row.querySelector('.organization-phone').textContent;
                    const taskCode = row.querySelector('.task-code').textContent;

                    organizations.push({
                        code: organizationCode,
                        name: organizationName,
                        email: organizationEmail,
                        phone: organizationPhone,
                        task_code: taskCode,
                        id: id,
                        task_id: taskId
                    });
                });
                // Gọi API một lần sau khi thu thập tất cả dữ liệu
                fetch('/save-assign-organizations', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            organizations: organizations
                        })
                    })
                    .then(response => response.json())
                    .then(data => {

                        if (data.success) {

                            fetchOrganizations(taskTargetId);
                            document.getElementById('assigned-area').style.display = 'block'
                        } else {
                            console.log('Đã xảy ra lỗi!');
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                    });
            });
        });

        function renderAssignTable(data) {
            const taskTargetId = document.getElementById('task-target-id').value;
            const taskTargetCode = document.getElementById('task-target-code').value;
            const tasksTableBody = document.getElementById('assigned-organizations');
            tasksTableBody.innerHTML = '';
            data.forEach(task => {
                // if (assignedOrgLst.includes(task.code)) return;
                // assignedOrgLst.push(task.code);
                const row = document.createElement('tr');

                // Tên công việc
                const taskCodeAssignCell = document.createElement('td');
                taskCodeAssignCell.textContent = taskTargetCode;
                taskCodeAssignCell.classList.add('task-code');


                const taskIdAssignCell = document.createElement('td');
                taskIdAssignCell.textContent = taskTargetId;
                taskIdAssignCell.classList.add('task-id');
                taskIdAssignCell.style.display = 'none';

                // Mã công việc
                const codeCell = document.createElement('td');
                codeCell.textContent = task.code;
                codeCell.classList.add('organization-code');

                // Tên công việc
                const nameCell = document.createElement('td');
                nameCell.textContent = task.name;
                nameCell.classList.add('organization-name');
                // Tên công việc
                const emailCell = document.createElement('td');
                emailCell.textContent = task.email;
                emailCell.classList.add('organization-email');

                // Tên công việc
                const phoneCell = document.createElement('td');
                phoneCell.textContent = task.phone;
                phoneCell.classList.add('organization-phone');

                row.appendChild(taskCodeAssignCell);
                row.appendChild(taskIdAssignCell);
                row.appendChild(codeCell);
                row.appendChild(nameCell);
                row.appendChild(emailCell);
                row.appendChild(phoneCell);


                tasksTableBody.appendChild(row);
            });
        }
        document.getElementById('check-all-organizations').addEventListener('change', function(event) {
            const checked = event.target.checked;
            document.querySelectorAll('#existing-organizations input.organization-checkbox').forEach(
                checkbox => {
                    checkbox.checked = checked;
                });
        });
    </script>
@endsection
