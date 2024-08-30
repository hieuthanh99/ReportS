<!-- resources/views/search/results.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
    <h1>Kết quả tìm kiếm</h1>

    @if($results->isEmpty())
        <p>Không có kết quả nào phù hợp với tìm kiếm của bạn.</p>
    @else
        @switch($searchIn)
            @case('documents')
                <h2>Kết quả tìm kiếm trong Văn bản</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->title }}</strong> - {{ $result->description }}
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('document_categories')
                <h2>Kết quả tìm kiếm trong Loại văn bản</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('tasks')
                <h2>Kết quả tìm kiếm trong Nhiệm vụ</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong> - {{ $result->details }}
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('categories')
                <h2>Kết quả tìm kiếm trong Danh mục</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('organization_types')
                <h2>Kết quả tìm kiếm trong Loại cơ quan</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('organizations')
                <h2>Kết quả tìm kiếm trong Cơ quan</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('task_groups')
                <h2>Kết quả tìm kiếm trong Nhóm nhiệm vụ</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('indicator_groups')
                <h2>Kết quả tìm kiếm trong Nhóm chỉ tiêu</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('positions')
                <h2>Kết quả tìm kiếm trong Chức vụ</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('users')
                <h2>Kết quả tìm kiếm trong Người dùng</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->name }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @case('report')
                <h2>Kết quả tìm kiếm trong Báo cáo</h2>
                <ul>
                    @foreach($results as $result)
                        <li>
                            <strong>{{ $result->title }}</strong>
                            <!-- Thêm các liên kết hoặc chi tiết khác nếu cần -->
                        </li>
                    @endforeach
                </ul>
                @break

            @default
                <p>Không có kết quả phù hợp với tìm kiếm của bạn.</p>
        @endswitch
    @endif
</div>
@endsection
