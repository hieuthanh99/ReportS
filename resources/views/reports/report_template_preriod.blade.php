@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white p-6 rounded-lg shadow-lg" style="margin-top: 10px;">
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
        session(['success' => null])
        @endphp
        <div id="reportTable"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var data = @json($data);
                var container = document.getElementById('reportTable');
                var hot = new Handsontable(container, {
                    width: '100%',
                
                    data: data,
                    rowHeaders: true,
                    colHeaders: true,
                    filters: true,
                
                    rowHeights: 23,
                    colWidths: 100,
                    autoWrapRow: true,
                    autoWrapCol: true,
                    licenseKey: 'non-commercial-and-evaluation'
                });
            });
        </script>
    </div>
@endsection
