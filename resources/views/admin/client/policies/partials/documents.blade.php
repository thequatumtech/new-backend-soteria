{{--
    Policy documents for the admin policy views, shown as "View ..." buttons (same design as home).
    $documentFields: ['Label' => value of a document column, ...]
    A column may hold one path, "path1,path2" or a JSON list (see getDocumentPaths()).
--}}
@php
    $documentItems = [];

    foreach ($documentFields as $label => $value) {
        $paths = getDocumentPaths($value);

        foreach ($paths as $index => $path) {
            $documentItems[] = [
                'label' => count($paths) > 1 ? $label . ' ' . ($index + 1) : $label,
                'url' => getDocumentUrl($path),
            ];
        }
    }
@endphp

<h6 class="section-title mt-4">Policy Documents</h6>

<div class="d-flex flex-wrap gap-2">
    @forelse($documentItems as $item)
        @if($item['url'])
            <a href="{{ $item['url'] }}" target="_blank" class="btn btn-outline-primary document-btn">
                View {{ $item['label'] }}
            </a>
        @else
            <span class="btn btn-outline-secondary document-btn disabled">
                {{ $item['label'] }} (not found)
            </span>
        @endif
    @empty
        <span class="text-muted">No policy documents available.</span>
    @endforelse
</div>
