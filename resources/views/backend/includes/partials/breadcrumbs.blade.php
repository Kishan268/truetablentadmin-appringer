@if($breadcrumbs)
    <ol class="breadcrumb" style="font-size: 0.875em;">
        <li class="breadcrumb-item">Home</li>

        @foreach($breadcrumbs as $breadcrumb)
            @if($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach

        @yield('breadcrumb-links')
    </ol>
@endif
