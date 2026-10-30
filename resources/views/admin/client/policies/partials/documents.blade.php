{{--
    Policy documents for the admin policy views.
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
                'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
            ];
        }
    }
@endphp

<h6 class="section-title mt-4">Policy Documents</h6>

<div class="d-flex flex-wrap gap-3">
    @forelse($documentItems as $item)
        <div class="text-center">
            <div class="detail-label mb-1">{{ $item['label'] }}</div>

            @if(!$item['url'])
                <span class="text-muted">Document not found</span>
            @elseif(in_array($item['extension'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], true))
                <a href="{{ $item['url'] }}" target="_blank" title="Open full size">
                    <img src="{{ $item['url'] }}" alt="{{ $item['label'] }}"
                        style="max-width: 140px; max-height: 110px; border: 1px solid #ddd; border-radius: 4px;">
                </a>
            @else
                <a href="{{ $item['url'] }}" target="_blank" class="btn btn-outline-primary document-btn">
                    View {{ strtoupper($item['extension'] ?: 'file') }}
                </a>
            @endif
        </div>
    @empty
        <span class="text-muted">No policy documents available.</span>
    @endforelse
</div>
