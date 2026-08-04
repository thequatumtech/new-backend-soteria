@if(Session::get('success'))
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x w-100">
    <div class="p-3 col-12 col-lg-8 mx-auto">
        <div class="toast align-items-center text-bg-primary border-0 col-12 col-lg-8 w-100" data-bs-delay="3000" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
            <div class="d-flex">
                <div class="toast-body px-5 fw-bold py-3" style="font-size: 1.375rem;">
                    {{ Session::get('success') }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        const toastLiveExample = document.getElementById('successToast');
        const toast = new bootstrap.Toast(toastLiveExample)
        toast.show()
    });
</script>
@php
Session::forget('success')
@endphp
@endif

@if(Session::get('error'))
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x w-100">
    <div class="p-3 col-12 col-lg-8 mx-auto">
        <div class="toast align-items-center text-bg-danger border-0 col-12 col-lg-8 w-100" data-bs-delay="3000" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
            <div class="d-flex">
                <div class="toast-body px-5 fw-bold py-3" style="font-size: 1.375rem;">
                    {{ Session::get('error') }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        const toastLiveExample = document.getElementById('errorToast');
        const toast = new bootstrap.Toast(toastLiveExample)
        toast.show()
    });
</script>
@php
Session::forget('error')
@endphp
@endif