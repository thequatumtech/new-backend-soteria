<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.part.title', ["title" => isset($title) ? $title : "Sotaria Backend"])

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/super-build/ckeditor.js"></script>

    @include('layouts.part.style')

    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 200px;
        }

        .ck-content .image {
            max-width: 80%;
            margin: 20px auto;
        }

        textarea.ceditor {
            visibility: hidden;
        }

        noscript textarea.ceditor {
            visibility: visible;
        }
    </style>
</head>

<body class="">
    <script>
        // Store all editor instances
        window.ckEditors = {};

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("textarea.ceditor").forEach((el) => {

                CKEDITOR.ClassicEditor.create(el, {
                    toolbar: {
                        items: [
                            'exportPDF', 'exportWord', '|',
                            'findAndReplace', 'selectAll', '|',
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline',
                            'code', 'subscript', 'superscript', 'removeFormat', '|',
                            'bulletedList', 'numberedList', 'todoList', '|',
                            'outdent', 'indent', '|',
                            'undo', 'redo', '-',
                            'fontSize', 'fontFamily', 'fontColor',
                            'fontBackgroundColor', 'highlight', '|',
                            'alignment', '|',
                            'link', 'insertImage', 'blockQuote', 'insertTable',
                            'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                            'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                            'textPartLanguage', '|',
                            'sourceEditing'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    placeholder: 'Start typing here...',
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
                        ]
                    },
                    fontFamily: {
                        options: [
                            'default',
                            'Arial, Helvetica, sans-serif',
                            'Courier New, Courier, monospace',
                            'Georgia, serif',
                            'Lucida Sans Unicode, Lucida Grande, sans-serif',
                            'Tahoma, Geneva, sans-serif',
                            'Times New Roman, Times, serif',
                            'Trebuchet MS, Helvetica, sans-serif',
                            'Verdana, Geneva, sans-serif'
                        ],
                        supportAllValues: true
                    },
                    fontSize: {
                        options: [10, 12, 14, 'default', 18, 20, 22],
                        supportAllValues: true
                    },
                    htmlSupport: {
                        allow: [{
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }]
                    },
                    htmlEmbed: {
                        showPreviews: true
                    },
                    link: {
                        decorators: {
                            addTargetToExternalLinks: true,
                            defaultProtocol: 'https://',
                            toggleDownloadable: {
                                mode: 'manual',
                                label: 'Downloadable',
                                attributes: {
                                    download: 'file'
                                }
                            }
                        }
                    },
                    mention: {
                        feeds: [{
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@candy',
                                '@chocolate', '@cookie', '@cream', '@donut',
                                '@fruitcake', '@gingerbread', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan',
                                '@pudding', '@sugar', '@sweet', '@wafer'
                            ],
                            minimumCharacters: 1
                        }]
                    },
                    removePlugins: [
                        'CKBox', 'CKFinder', 'EasyImage',
                        'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                        'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments',
                        'TrackChanges', 'TrackChangesData', 'RevisionHistory',
                        'Pagination', 'WProofreader', 'MathType'
                    ]
                }).then(editor => {

                    window.ckEditors[el.id] = editor;

                    const editorContainer = editor.ui.view.editable.element.parentElement;
                    if (editorContainer) {
                        editorContainer.style.visibility = 'visible';
                    }

                }).catch(error => {
                    console.error(error);
                    el.style.visibility = 'visible';
                });
            });


            // ===============================
            // EDIT BUTTON CLICK HANDLER
            // ===============================
            document.querySelectorAll('.editbtn').forEach(btn => {
                btn.addEventListener('click', function() {

                    const id = this.getAttribute('data-id');
                    const message = decodeHTMLEntities(this.getAttribute('data-message'));

                    document.getElementById('edit_id').value = id;

                    if (window.ckEditors['edit_message']) {
                        window.ckEditors['edit_message'].setData(message);
                    }
                });
            });
        });


        function decodeHTMLEntities(text) {
            const textarea = document.createElement('textarea');
            textarea.innerHTML = text;
            return textarea.value;
        }
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