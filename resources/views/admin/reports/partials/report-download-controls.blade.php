<div class="d-flex gap-2 align-items-center">
    <button type="button" class="btn btn-danger report-download-button" data-download-format="pdf" data-download-url="{{ $pdfRoute }}" data-file-prefix="{{ $filePrefix ?? 'Report' }}">
        <i class="fas fa-file-pdf"></i> PDF
    </button>
    <button type="button" class="btn btn-success report-download-button" data-download-format="excel" data-download-url="{{ $excelRoute }}" data-file-prefix="{{ $filePrefix ?? 'Report' }}">
        <i class="fas fa-file-excel"></i> Excel
    </button>
    <a href="javascript:void(0)" id="toggleForm" class="btn pe-0">
        <img src="{{ asset('img/icon-add.png') }}" alt="">
    </a>
</div>
