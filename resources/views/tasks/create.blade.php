@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {!! Breadcrumbs::render('create.tasks.byType', $type) !!}
            </ol>
        </nav>
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
        @php
        $text = "chỉ tiêu";
        if($type == 'task') $text = "nhiệm vụ";

        @endphp
        {{-- <h1 class="text-3xl font-bold mb-6 text-gray-800">Thêm nhiệm vụ/chỉ tiêu</h1> --}}

        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="task-create-form">
            @csrf
            <input type="hidden" id="type" name="type" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ $type }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" >
                <!-- Cột trái -->
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 text-sm font-medium mb-2">Mã {{ $text }} <span class="text-red-500">*</span></label>
                    <input type="text" readonly id="code" name="code" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('code') }}">
                </div>
                <div class="mb-4">
                    <label for="document_id" class="block text-gray-700 text-sm font-medium mb-2">Văn bản <span class="text-red-500">*</span></label>

                    <select name="document_id" id="document_id" class="form-input w-full border border-gray-300 rounded-lg p-2" required 
                    oninvalid="this.setCustomValidity('Vui lòng chọn văn bản.')" 
                    oninput="setCustomValidity('')">
                        <option value="" data-code="">Chọn văn bản
                        </option>
                        @foreach ($documents as $item)
                            <option value="{{ $item->id }}" data-code="{{ $item->document_code }}" {{ old('document_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->document_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="mb-4">
                    <label for="type_id" class="block text-gray-700 text-sm font-medium mb-2">Nhóm {{ $text }} <span class="text-red-500">*</span></label>
                 
                    <select name="type_id" id="type_id" class="form-input w-full border border-gray-300 rounded-lg p-2" required 
                    oninvalid="this.setCustomValidity('Vui lòng chọn nhóm.')" 
                    oninput="setCustomValidity('')">
                        <option value="" data-code="">Chọn nhóm {{ $text }}</option>
                        @foreach ($typeTask as $item)
                            <option value="{{ $item->id }}" {{ old('type_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
          
                </div>

                <div class="mb-4">
                    <label for="cycle_type" class="block text-gray-700 text-sm font-medium mb-2">Chu kỳ báo cáo <span class="text-red-500">*</span></label>
                    <select id="cycle_type" name="cycle_type" class="form-select w-full border border-gray-300 rounded-lg p-2" required
                    oninvalid="this.setCustomValidity('Vui lòng chọn chu kỳ báo cáo.')" 
                    oninput="setCustomValidity('')">
                        <!-- <option value="1" {{ old('cycle_type') == '1' ? 'selected' : '' }}>Tuần</option> -->
                        <option value="2" {{ old('cycle_type') == '2' ? 'selected' : '' }}>Tháng</option>
                        <!-- <option value="3" {{ old('cycle_type') == '3' ? 'selected' : '' }}>Quý</option>
                        <option value="4" {{ old('cycle_type') == '4' ? 'selected' : '' }}>Năm</option> -->
                    </select>

                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên {{ $text }} <span class="text-red-500">*</span></label>
                    <textarea id="name" name="name" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4" required
                    oninvalid="this.setCustomValidity('Vui lòng nhập tên.')" 
                    oninput="setCustomValidity('')">{{ old('name') }}</textarea>
                </div>
                @if($type == 'task')
                <div class="mb-4">
                    
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Loại nhiệm vụ<span class="text-red-500">*</span></label>
                    <select id="task_type" name="task_type" class="form-input w-full border border-gray-300 rounded-lg p-2" style="margin-bottom: 10px">
                        <option value="" disabled selected>-- Chọn loại nhiệm vụ --</option>
                        <option value="timed">Có thời hạn</option> <!-- Giá trị tiếng Anh: "timed" -->
                        <option value="regular">Thường xuyên</option> <!-- Giá trị tiếng Anh: "regular" -->
                    </select>
                   
                </div>
                @else
                <!-- <div class="mb-4">
                    
                    <label for="task_type" class="block text-gray-700 text-sm font-medium mb-2">Loại chỉ tiêu<span class="text-red-500">*</span></label>
                   
                    <select id="target_type" name="target_type" class="form-input w-full border border-gray-300 rounded-lg p-2" style="margin-bottom: 10px">
                        <option value="" disabled selected>-- Chọn loại chỉ tiêu --</option>
                        <option value="single">Đơn</option> 
                        <option value="aggregate">Tổng hợp</option> 
                    </select>
                    
                </div> -->
                @endif
                @if($type == 'target')
                <div class="mb-4">
                    <label for="unit" class="block text-gray-700 text-sm font-medium mb-2">Đơn vị tính<span class="text-red-500">*</span></label>
                    <select id="unit" name="unit" class="w-full p-2 border border-gray-300 rounded-md" onchange="toggleCustomInput(this)">
                        @foreach ($units as $item)
                            <option value="{{ $item->id }}" {{ old('unit') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                        <option value="0">Khác</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="target" class="block text-gray-700 text-sm font-medium mb-2">Chỉ tiêu <span class="text-red-500">*</span></label>
                    <input type="text"  placeholder="Nhập chỉ tiêu"
                     id="target" name="target" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('target') }}">
                </div>
                @else
                
                <div class="mb-4">
                    {{-- onchange="changeResultType(this.value)" --}}
                    <label for="issuing_department" class="block text-gray-700 text-sm font-medium mb-2">Kết quả:</label>
                    <select name="result_type" id="result_type"  class="form-input w-full border border-gray-300 rounded-lg p-2" style="margin-bottom: 10px">
                        @foreach ($workResultTypes as $idx => $item)
                            <option value="{{ $item->key }}">
                                {{ $item->value }}
                            </option>
                        @endforeach
                    </select>
                    {{-- <div id="result-area">
                        <input type="hidden" value="Yes" id="issuing_department" name="request_results">
                        <input type="radio" id="yes" name="yes" value="Yes" checked onclick="selectType(this.value)">
                        <label for="yes">Yes</label><br>
                        <input type="radio" id="no" name="no" value="No" onclick="selectType(this.value)">
                        <label for="no">No</label><br>
                    </div> --}}
                </div>
                <div class="mb-4">
                    <label for="request_results_task" class="block text-gray-700 text-sm font-medium mb-2">Kết quả yêu cầu <span class="text-red-500">*</span></label>
                    <input type="text" id="request_results_task" name="request_results_task" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả yêu cầu...">
                </div>
                @endif
               
                <div class="mb-4 hidden" id="custom-unit">
                    <label for="custom_unit" class="block text-gray-700 font-medium mb-2">Nhập đơn vị tùy chỉnh <span class="text-red-500">*</span></label>
                    <input type="text" id="custom_unit" name="custom_unit" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Nhập đơn vị khác...">
                </div>
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày hoàn thành <span class="text-red-500">*</span></label>
                    <input type="date"  placeholder="dd-mm-yyyy"
                    min="1997-01-01" max="2100-12-31" id="end_date" name="end_date" class="form-input w-full border border-gray-300 rounded-lg p-2" value="{{ old('end_date') }}" required>
                </div>
                <div class="mb-4" style="display: none">
                    <label for="category_id" class="block text-gray-700 text-sm font-medium mb-2">Phân loại <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" class="form-input w-full border border-gray-300 rounded-lg p-2" required>
                        @foreach ($categories as $item)
                            <option value="{{ $item->CategoryID }}" {{ old('category_id') == $item->CategoryID ? 'selected' : '' }}>
                                {{ $item->CategoryName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                
            </div> -->
            <div class="mt-4 text-right" style="justify-content: space-between; display: flex;">
                <button type="button"  id="back" style="padding: 10px 20px" class="bg-gray-500 text-white px-10 py-2 rounded-lg shadow-lg hover:bg-gray-600 transition duration-300 mt-4">Quay lại</button>
                <button  type="submit"  style="padding: 10px 20px" class="bg-blue-600 text-white px-10 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">Lưu & sang giao việc</button>
            </div>
        </form>

        <script>
             function toggleCustomInput(selectElement) {
            var customInput = document.getElementById('custom-unit');
            if (selectElement.value === '0') {
                customInput.classList.remove('hidden');
            } else {
                customInput.classList.add('hidden');
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
            document.querySelector('form').addEventListener('submit', function() {
                const selectType = document.getElementById("type").value;
                const hiddenField = document.getElementById("request_results");
                let combinedValue = "";

                if (selectType === "task") {
                    combinedValue = document.getElementById("request_results_area").value;
                } else if (selectType === "target") {
                    combinedValue = document.getElementById("request_results_number").value;
                }
                hiddenField.value = combinedValue;
            });

            function toggleFields() {
                const selectType = document.getElementById("type").value;
                const textAreaDiv = document.getElementById("text_area_div");
                const numberInputDiv = document.getElementById("number_input_div");

                if (selectType === "task") {
                    textAreaDiv.classList.remove("hidden");
                    numberInputDiv.classList.add("hidden");
                } else if (selectType === "target") {
                    textAreaDiv.classList.add("hidden");
                    numberInputDiv.classList.remove("hidden");
                }
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
                document.getElementById('task-create-form').addEventListener('submit', function() {
                    // Hiển thị loader khi form được gửi
                    document.getElementById('loading').classList.remove('hidden');
                });

            function changeResultType(value) {
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

                }
                else if(value == keys[4] && workType == 'task') {
                    element.innerHTML = '<textarea id="issuing_department" style="height: 62px" name="request_results" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4"></textarea>'
                }
                else if(value == keys[1]) {
                    element.innerHTML = '<input id="issuing_department" type="number" name="request_results" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả" min="0" max="99999999999999" value="" oninput="this.value = this.value.replace(\'.\', \'\')" step="1">'
                }
                else {
                    element.innerHTML = '<input id="issuing_department" type="number" name="request_results" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả" min="0" max="99999999999999" value="" step="any">'
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
        </script>
    </div>
@endsection
