@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            {!! Breadcrumbs::render('update.tasks.byType', $type) !!}
        </ol>
    @if ($errors->any())
        <div class="error-message bg-red-500 text-white p-4 rounded-lg mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="error-message bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="success-message bg-green-500 text-white p-4 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-hidden">
        <div class="p-6">
        @php
        $text = "Chỉ tiêu";
        if($type == 'task') $text = "Nhiệm vụ";

        @endphp
        <form id="form-update" action="{{ route('tasks.update.taskTarget', ['code' => $taskTarget->code, 'type' => $taskTarget->type]) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <input type="hidden" name="type" id="type" value="{{ $type }}"/>
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 text-sm font-medium mb-2">Mã {{ $text }}:</label>
                        <input type="text" id="code" name="code" readonly
                            value="{{ $taskTarget->code }}"
                            class="form-input w-full border border-gray-300 rounded-lg p-2">
           
                </div>
                <div class="mb-4">
                    <label for="document_id" class="block text-gray-700 text-sm font-medium mb-2">Văn bản:</label>
                        <select name="document_id" id="document_id" required
                            class="form-input w-full border border-gray-300 rounded-lg p-2 select2"
                            oninvalid="this.setCustomValidity('Vui lòng chọn văn bản.')" 
                    oninput="setCustomValidity('')">
                            @foreach ($documents as $item)
                                <option value="{{ $item->id }}" data-code="{{ $item->document_code }}"
                                    {{ $item->id == $taskTarget->document_id? 'selected' : '' }}>
                                    {{ $item->document_name }}
                                </option>
                            @endforeach
                        </select>
                  
                </div>
                <div class="mb-4">
                    <label for="type_id" class="block text-gray-700 text-sm font-medium mb-2">Nhóm {{ $text }} <span class="text-red-500">*</span></label>
                 
                    <select name="type_id" id="type_id" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" required 
                    oninvalid="this.setCustomValidity('Vui lòng chọn nhóm.')" 
                    oninput="setCustomValidity('')">
                        <option value="" data-code="">Chọn nhóm {{ $text }}</option>
                        @foreach ($typeTask as $item)
                            <option value="{{ $item->id }}" {{ $taskTarget->type_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
          
                </div>
                <div class="mb-4">
                    <label for="cycle_type" class="block text-gray-700 text-sm font-medium mb-2">Chu kỳ báo cáo <span class="text-red-500">*</span></label>
                    <select id="cycle_type" name="cycle_type" class="form-select w-full border border-gray-300 rounded-lg p-2 select2" required
                    oninvalid="this.setCustomValidity('Vui lòng chọn chu kỳ báo cáo.')" 
                    oninput="setCustomValidity('')">
                        <option value="1" {{ $taskTarget->cycle_type == '1' ? 'selected' : '' }}>Tuần</option>
                        <option value="2" {{ $taskTarget->cycle_type == '2' ? 'selected' : '' }}>Tháng</option>
                        <option value="3" {{ $taskTarget->cycle_type == '3' ? 'selected' : '' }}>Quý</option>
                        <option value="4" {{ $taskTarget->cycle_type == '4' ? 'selected' : '' }}>Năm</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên {{ $text }}:</label>
                    <textarea required id="name" name="name" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4">{{ $taskTarget->name }}</textarea>
                </div>
                @if($type == 'task')
                <div class="mb-4">
                    
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Loại nhiệm vụ<span class="text-red-500">*</span></label>
                    <select id="task_type" name="task_type" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" style="margin-bottom: 10px">
                        <option value="timed" {{ $taskTarget->task_type == 'timed' ? 'selected' : '' }}>Có thời hạn</option>
                        <option value="regular" {{ $taskTarget->task_type == 'regular' ? 'selected' : '' }}>Thường xuyên</option>
                    </select>
                   
                </div>
                @else
                <div class="mb-4">
                    
                    <label for="task_type" class="block text-gray-700 text-sm font-medium mb-2">Loại chỉ tiêu<span class="text-red-500">*</span></label>
                   
                    <select id="target_type" name="target_type" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" style="margin-bottom: 10px">
                        <option value="single" {{ $taskTarget->target_type == 'single' ? 'selected' : '' }}>Đơn</option> <!-- "Đơn" -> "single" -->
                        <option value="aggregate" {{ $taskTarget->target_type == 'aggregate' ? 'selected' : '' }}>Tổng hợp</option> <!-- "Tổng hợp" -> "aggregate" -->
                    </select>
                    
                </div>
                @endif
                @if($type == 'target')
               <div class="mb-4">
                    <label for="unit" class="block text-gray-700 text-sm font-medium mb-2">Đơn vị tính<span class="text-red-500">*</span></label>
                    <select id="unit" name="unit" class="w-full p-2 border border-gray-300 rounded-md select2" onchange="toggleCustomInput(this)">
                        @foreach ($units as $item)
                            <option value="{{ $item->id }}" {{ $taskTarget->unit == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                        <option value="0">Khác</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="target" class="block text-gray-700 text-sm font-medium mb-2">Chỉ tiêu <span class="text-red-500">*</span></label>
                    <input type="text"  placeholder="Nhập chỉ tiêu"
                     id="target" name="target" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ $taskTarget->target }}">
                </div>
                @else
                <div class="mb-4">
                    {{-- onchange="changeResultType(this.value)" --}}
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Kết quả:</label>
                    <select name="result_type" id="result_type" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" style="margin-bottom: 10px">
                        @foreach ($workResultTypes as $idx => $item)
                            @continue($type != 'task' && $idx == 4)
                            <option value="{{ $item->key }}" {{ $taskTarget->result_type == $item->key ? 'selected' : '' }}>
                                {{ $item->value }}
                            </option>
                        @endforeach
                    </select>
    
                </div>
                <div class="mb-4">
                    <label for="request_results_task" class="block text-gray-700 text-sm font-medium mb-2">Kết quả yêu cầu <span class="text-red-500">*</span></label>
                    <input type="text" id="request_results_task" value="{{ $taskTarget->request_results_task }}" name="request_results_task" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả yêu cầu...">
                </div>
                @endif
             
                <div class="mb-4 hidden" id="custom-unit">
                    <label for="custom_unit" class="block text-gray-700 font-medium mb-2">Nhập đơn vị tùy chỉnh <span class="text-red-500">*</span></label>
                    <input type="text" id="custom_unit" name="custom_unit" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Nhập đơn vị khác...">
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày kết thúc:</label>
                    <input type="date" id="end_date" name="end_date" placeholder="dd-mm-yyyy"
                    min="1997-01-01" max="2100-12-31"
                  required
                    value="{{ $taskTarget->end_date }}"
                    class="form-input w-full border border-gray-300 rounded-lg p-2">

                </div>
                <div class="mb-4" style="display: none">
                    <label for="category_id" class="block text-gray-700 text-sm font-medium mb-2">Phân loại <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" class="form-input w-full border border-gray-300 rounded-lg p-2 select2" required>
                        @foreach ($categories as $item)
                            <option value="{{ $item->CategoryID }}" {{ $taskTarget->category_id == $item->CategoryID ? 'selected' : '' }}>
                                {{ $item->CategoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
             
            <div class="mt-4 flex" style="justify-content: space-between">
                <button type="button"  id="back"  class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 mt-4">Quay lại</button>
                <div>
                    <a href="{{ route('tasks.assign-organizations', ['taskTargetId' => $taskTarget->id]) }}" style="margin: 0 20px; padding: 10px"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">
                         Giao việc
                     </a>
                    <button type="submit" id="save-button"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">Lưu
                    </button>
                </div>

            </div>
        </form>
        <table class="min-w-full bg-white border border-gray-300" style="margin-top: 20px">
            <thead class="bg-gray-100 border-b border-gray-300" >
                <tr>
                    <th colspan="8" class="border border-gray-300 py-3 px-6 text-left font-medium text-center">Cơ quan, tổ chức đã được giao</th>
                    
                </tr>
                <tr>
                    <th class="border border-gray-300 py-3 px-6 text-left font-medium">Mã cơ quan, tổ chức</th>
                    <th class="border border-gray-300 py-3 px-6 text-left font-medium">Tên cơ quan, tổ chức</th>
                    <th class="border border-gray-300 py-3 px-6 text-left font-medium">Loại cơ quan, tổ chức</th>
                    <th class="border border-gray-300 py-3 px-6 text-left font-medium">Số điện thoại</th>
                    <th class="border border-gray-300 py-3 px-6 text-left font-medium">Email</th>
                    <th class="border border-gray-300 py-3 px-6 text-left font-medium text-center">
                        Website
                    </th>
                    <th class="py-3 px-6 text-left font-medium text-center">
                       Địa chỉ
                    </th>
                    <th class="py-3 px-6 text-left font-medium text-center">
                       Xóa
                     </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taskResult as $index => $item)
                    <tr class="border-b border-gray-200">
                   
                        <td class="py-3 border border-gray-300 px-6">{{ $item->organization->code }}</td>
                        <td class="py-3 border border-gray-300 px-6">{{ $item->organization->name }}</td>
                        <td class="py-3 border border-gray-300 px-6">
                            {{ $item->organization->organizationType->type_name ?? '' }}
                        </td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $item->organization->phone }}</td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $item->organization->email }}</td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $item->organization->website }}</td>
                        <td class="py-3 border border-gray-300 px-6"> {{ $item->organization->address }}</td>
                        <td class="py-3 border border-gray-300 px-6 text-center">
                            <form id="delete-form-{{ $item->id }}" action="{{ route('tasks.delete.organization', ['id_task_criteria' => $item->id_task_criteria, 'type' => $taskTarget->type, 'id' => $item->id ]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 transition duration-300 ml-2"
                                    onclick="confirmDelete({{ $item->id }})">
                                    <i class="fas fa-trash"></i> <!-- Biểu tượng cho "Xóa" -->
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $taskResult->links() }}
        </div>
        </div>
     </div>
    </div>
<script>
      function toggleCustomInput(selectElement) {
            var customInput = document.getElementById('custom-unit');
            if (selectElement.value === '0') {
                customInput.classList.remove('hidden');
            } else {
                customInput.classList.add('hidden');
            }
        }
    function changeResultType(value) {
        let selectedType = {!! json_encode($taskTarget->result_type) !!};
        let keys = {!! json_encode($keyConstants) !!};
        let workType = {!! json_encode($type) !!};
        let element = document.getElementById('result-area');
        if(value == keys[0]) {
            element.innerHTML =
                '            <input type="hidden" value="Yes" id="issuing_department" name="request_results">' +
                '            <input type="radio" id="yes" name="yes" value="Yes" onclick="selectType(this.value)" checked>\n' +
                '            <label for="yes">Yes</label><br>\n' +
                '            <input type="radio" id="no" name="no" value="No" onclick="selectType(this.value)">\n' +
                '            <label for="no">No</label><br>'

            if(selectedType === value) {
                let yesBtn = document.getElementById('yes');
                let noBtn = document.getElementById('no');
                let selectedValue = {!! json_encode($taskTarget->request_results) !!};
                if(selectedValue == yesBtn.value) {
                    yesBtn.setAttribute('checked', 'checked')
                    noBtn.checked = false
                }
                else {
                    yesBtn.checked = false
                    noBtn.setAttribute('checked', 'checked')
                }
            }

        }
        else if(value == keys[4] && workType == 'task') {
            element.innerHTML = '<textarea id="issuing_department" style="height: 62px" name="request_results" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4">' + (selectedType === value ? '{{$taskTarget->request_results }}' :'') + '</textarea>'
        }
        else if(value == keys[1]) {
            element.innerHTML = '<input id="issuing_department" type="number" name="request_results" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả" min="0" max="99999999999999" value="' + (selectedType === value ? '{{$taskTarget->request_results }}' :'') + '" oninput="this.value = this.value.replace(\'.\', \'\')" step="1">'
        }
        else if(value == keys[2] || value == keys[3]) {
            element.innerHTML = '<input id="issuing_department" type="number" name="request_results" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả" min="0" max="99999999999999" value="' + (selectedType === value ? '{{$taskTarget->request_results }}' :'') + '" step="any">'
        }
    }

    function selectType(value) {
        document.getElementById('issuing_department').value = value
        let yesBtn = document.getElementById('yes');
        let noBtn = document.getElementById('no');
        if(value == yesBtn.value) {
            yesBtn.setAttribute('checked', 'checked')
            noBtn.checked = false
        }
        else {
            yesBtn.checked = false
            noBtn.setAttribute('checked', 'checked')
        }
    }

    document.getElementById('document_id').addEventListener('change', generateTaskCode);
        function generateTaskCode() {
            const documentSelect = document.getElementById('document_id');
            const selectedOption = documentSelect.options[documentSelect.selectedIndex];
            const documentCode = selectedOption.getAttribute('data-code');
            const selectType = document.getElementById("type").value;
            const randomNum = Math.floor(1000000 + Math.random() * 9000000);
            let s = "";
            if (selectType === "task") {
                s = "NV";
            } else if (selectType === "target") {
                s = "CT";
            }
            const taskCode = `${documentCode}-${s}-${randomNum}`;
            
            // Gán mã nhiệm vụ vào input hidden
            document.getElementById('code').value = taskCode;
        }
    document.getElementById('back').addEventListener('click', function(event) {
        event.preventDefault();
        var type = document.getElementById('type');
        var selectedValue = type.value;
        // Chuyển hướng đến URL tương ứng với giá trị được chọn
        if (selectedValue) {
            window.location.href = `/tasks/type/${selectedValue}`;
        }
    });
    function confirmDelete(itemId) {
    Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: 'Xác nhận xóa!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Có, xóa!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form-' + itemId);
            if (form) {
                form.submit();
            } else {
                console.error('Form not found');
            }
        }
    });
}
</script>
@endsection
