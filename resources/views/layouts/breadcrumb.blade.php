<div class="page-heading mb-1">
    <h3>{{ $breadcrumb->title }}</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumb->list as $key => $value)
                @if ($key == count($breadcrumb->list) - 1)
                    <li class="breadcrumb-item active">{{ $value }}</a></li>
                @else
                    <li class="breadcrumb-item">{{ $value }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
