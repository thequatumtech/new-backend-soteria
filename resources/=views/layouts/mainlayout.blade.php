<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.part.title', ["title" => isset($title) ? $title : "Sotaria Backend"])

    <script src="https://cdn.tiny.cloud/1/agv9xsmlm07hok6b2v9b7vmb7vnkkh0zp66tgkyj136a9xb6/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Google Font CDN -->
   @include('layouts.part.style')
</head>
<body class="">
<script>
  tinymce.init({
    selector: 'textarea',
    // plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate mentions tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
    plugins : "link lists",
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link table mergetags | align lineheight | checklist numlist bullist indent outdent |removeformat',
    file_browser_callback_types: 'file image media'

  });
</script>
    <div class="page-wrapper chiller-theme toggled">
        @include('layouts.part.sidebar')
        <main class="page-content">
            <div class="col main d-flex flex-column">
                @include('layouts.part.nav')

                @yield('content')
            </div>
            @include('layouts.part.script')
            @include("layouts.part.toast")

        </main>
    </div>
</body>
</html>
