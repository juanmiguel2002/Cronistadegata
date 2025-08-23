@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Nou Article</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Inici</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Nou Article
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.posts') }}" class="btn btn-primary">Tots els articles</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form id="addPostForm" action="{{ route('admin.createPost') }}" method="POST" autocomplete="off" enctype="multipart/form-data" >
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card card-box mb-2">
                    <div class="card-body">
                        <div class="form-group">
                            <label for=""><b>Títol</b></label>
                            <input type="text" name="title" id="titulo" class="form-control" placeholder="Títol article" required oninput="contarCaracteres()">
                            <small class="text-sm text-gray-500 mt-1">
                                Caracteres: <span id="contador">0</span>/255    
                            </small>
                            <small class="text-danger text-error title_error"></small>
                        </div>
                        <div class="form-group">
                            <label for=""><b>Text</b></label>
                            <textarea name="content" id="content" class="form-control" rows="20" cols="40"></textarea>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card card-box mb-2">
                        <div class="card-header weight-500">Carrousel</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for=""><span>Imatges del Carrousel</span></label>
                                <input type="file" name="images[]" id="images" class="form-control-file form-control" multiple>
                                <small class="text-danger error-text "></small>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card card-box mb-2">
                    <div class="card-header weight-500">SEO</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for=""><span>Post meta Keywords:</span> <small>(Separados por coma.)</small></label>
                            <textarea name="meta_keywords" cols="30" rows="10" class="form-control" placeholder="Enter post meta description..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for=""><span>Post meta description:</span></label>
                            <textarea name="meta_descripcion" cols="30" rows="10" class="form-control" placeholder="Enter post meta description..." ></textarea>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="col-md-3">
                <div class="card card-box mb-2">
                    <div class="card-body">
                        <div class="form-group">
                            <label for=""><strong>Categories</strong>:</label>
                            <select name="category" class="custom-select form-control" required>
                                {!! $categories !!}
                            </select>
                            <small class="text-danger text-error category_error"></small>
                        </div>
                        <div class="form-group">
                            <label for=""><span>Imatge destacada</span></label>
                            <input type="file" name="featured_image" id="featured_image" class="form-control-file form-control" height="auto">
                            <small class="text-danger error-text featured_image_error"></small>
                        </div>
                        <div class="d-block mb-3" style="max-width: 250px">
                            <img src="" alt="" id="preview-featured-image" class="img-thumbnail" >
                        </div>
                        {{-- <div class="form-group">
                            <label for=""><span>Tags:</span></label>
                            <input type="text" name="tags" class="form-control" data-role="tagsinput">
                            <small class="text-danger error-text tags_error"></small>
                        </div> --}}
                        <hr>
                        <div class="form-group">
                            <label for=""><span>Visibility:</span></label>
                            <div class="custom-control custom-radio mb-5">
                                <input type="radio" name="visibility" id="customRadio1" class="custom-control-input" value="1" checked>
                                <label class="custom-control-label" for="customRadio1">Public</label>
                            </div>
                            <div class="custom-control custom-radio mb-5">
                                <input type="radio" name="visibility" id="customRadio2" class="custom-control-input" value="0">
                                <label class="custom-control-label" for="customRadio2">Private</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Publicar</button>
        </div>
    </form>
@endsection
@push('styles')
    <link rel="stylesheet" href="/back/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
@endpush
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.getElementById('featured_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-featured-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // Mostrar la nueva imagen
                };
                reader.readAsDataURL(file);
            }
        });
        ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', '|', 'undo', 'redo'
                ]
            },
            language: 'es', // Cambiar el idioma, por ejemplo: 'es' para español
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'
                ]
            },
            licenseKey: '', // Puedes dejarlo vacío si usas la versión gratuita

        })
        .then(function(editor){
            editor.model.document.on('change:data', () => {
                document.querySelector('#content').value = editor.getData();
            });
        })
        .catch( error => {
            console.error( error );
        });
        function contarCaracteres() {
            const input = document.getElementById("titulo");
            const contador = document.getElementById("contador");
            contador.textContent = input.value.length;
        }
    </script>
@endpush
