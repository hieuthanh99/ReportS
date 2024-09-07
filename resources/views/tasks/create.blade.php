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
        $text = "Chỉ tiêu";
        if($type == 'task') $text = "Nhiệm vụ";

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
                    <label for="type_id" class="block text-gray-700 text-sm font-medium mb-2">Nhóm {{ $text }} <span class="text-red-500">*</span></label>
                 
                    <select name="type_id" id="type_id" class="form-input w-full border border-gray-300 rounded-lg p-2" required >
                        <option value="" data-code="">Chọn loại {{ $text }}</option>
                        @foreach ($typeTask as $item)
                            <option value="{{ $item->id }}" {{ old('type_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
          
                </div>
             
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div class="flex gap-6 mb-4">
                    <div class="flex-1">
                        <label for="document_id" class="block text-gray-700 text-sm font-medium mb-2">Văn bản <span class="text-red-500">*</span></label>
                        
                        <select name="document_id" id="document_id" class="form-input w-full border border-gray-300 rounded-lg p-2" required >
                            <option value="" data-code="">Chọn văn bản
                            </option>
                            @foreach ($documents as $item)
                                <option value="{{ $item->id }}" data-code="{{ $item->document_code }}" {{ old('document_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->document_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="cycle_type" class="block text-gray-700 text-sm font-medium mb-2">Chu kỳ báo cáo <span class="text-red-500">*</span></label>
                        <select id="cycle_type" name="cycle_type" class="form-select w-full border border-gray-300 rounded-lg p-2" required>
                            <option value="1" {{ old('cycle_type') == '1' ? 'selected' : '' }}>Tuần</option>
                            <option value="2" {{ old('cycle_type') == '2' ? 'selected' : '' }}>Tháng</option>
                            <option value="3" {{ old('cycle_type') == '3' ? 'selected' : '' }}>Quý</option>
                            <option value="4" {{ old('cycle_type') == '4' ? 'selected' : '' }}>Năm</option>
                        </select>
                    </div>
                   
                   
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" >
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Tên {{ $text }} <span class="text-red-500">*</span></label>
                        <textarea id="name" name="name" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4" required>{{ old('name') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <input type="hidden" id="request_results" name="request_results" value="">
                        @if($type == 'task')
                        <div id="text_area_div">
                            <label for="request_results_area" class="block text-gray-700 text-sm font-medium mb-2">Kết quả</label>
                            <textarea id="request_results_area" name="request_results_area" class="form-input w-full border border-gray-300 rounded-lg p-2 resize-none" rows="4">{{ old('required_result') }}</textarea>
                        </div>
                        @else
                        <div id="number_input_div" >
                            <label for="request_results_number" class="block text-gray-700 text-sm font-medium mb-2">Kết quả số</label>
                            <input type="number" name="request_results_number" id="request_results_number" class="form-input w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập kết quả" min="0" max="100" value="{{ old('required_result') }}" step="any">
                        </div>
                        @endif
                    </div>
                   
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 text-sm font-medium mb-2">Ngày Kết Thúc <span class="text-red-500">*</span></label>
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
            <div class="mt-4 text-right" style="justify-content: space-between; display: flex;">
                <button type="button"  id="back" style="padding: 10px 20px" class="bg-gray-500 text-white px-10 py-2 rounded-lg shadow-lg hover:bg-gray-600 transition duration-300 mt-4">Quay lại</button>
                <button  type="submit"  style="padding: 10px 20px" class="bg-blue-600 text-white px-10 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 mt-4">Lưu & sang giao việc</button>
            </div>
        </form>

        <script>
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
        </script>
    </div>
@endsection
