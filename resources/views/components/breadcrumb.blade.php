@props(['breadcrumbs' => []])

<nav class="flex items-center text-gray-700" aria-label="Breadcrumb">
    <ol class="flex space-x-2">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                <li class="text-gray-500">{{ $breadcrumb['title'] }}</li>
            @else
                <li>
                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-blue-600">
                        {{ $breadcrumb['title'] }}
                    </a>
                    <span class="mx-2">></span>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
