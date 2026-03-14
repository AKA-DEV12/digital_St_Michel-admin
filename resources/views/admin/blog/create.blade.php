@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">{{ isset($post) ? 'Modifier l\'article' : 'Nouvel Article' }}</h1>
                <p class="text-secondary">Rédigez et publiez du contenu de haute qualité.</p>
            </div>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Annuler
            </a>
        </div>
    </div>

    <form action="{{ isset($post) ? route('admin.blog.update', $post) : route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($post)) @method('PUT') @endif

        <div class="row g-4 animate-fade-in" style="animation-delay: 0.1s">
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white">
                    <div class="mb-4">
                        <label for="title" class="form-label small fw-bold text-secondary">Titre de l'article</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg rounded-3 border-gray-200 fw-bold" value="{{ old('title', $post->title ?? '') }}" required placeholder="Un titre percutant...">
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <label for="editor" class="form-label small fw-bold text-secondary">Contenu</label>
                        <div class="editor-container">
                            <textarea name="content" id="editor">{{ old('content', $post->content ?? '') }}</textarea>
                        </div>
                        @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-4">Publication & Catégorie</h6>
                    
                    <div class="mb-4">
                        <label for="blog_category_id" class="form-label small fw-bold text-secondary">Catégorie</label>
                        <select name="blog_category_id" id="blog_category_id" class="form-select rounded-3 border-gray-200">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('blog_category_id', $post->blog_category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label small fw-bold text-secondary">Statut</label>
                        <select name="status" id="status" class="form-select rounded-3 border-gray-200">
                            <option value="draft" {{ (old('status', $post->status ?? '') == 'draft') ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ (old('status', $post->status ?? '') == 'published') ? 'selected' : '' }}>Publié</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold text-secondary ms-2" for="is_featured">Mettre cet article à la une</label>
                    </div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $post->is_popular ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold text-secondary ms-2" for="is_popular">Marquer comme populaire</label>
                    </div>
                </div>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-4">Tags</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <div class="form-check tag-check">
                                <input class="form-check-input d-none" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                    {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'checked' : '' }}>
                                <label class="form-check-label badge rounded-pill border py-2 px-3 cursor-pointer transition-all" for="tag_{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @if($tags->isEmpty())
                        <p class="text-secondary small mb-0 italic">Aucun tag disponible. <a href="{{ route('admin.blog.tags') }}" class="text-primary">En créer un ?</a></p>
                    @endif
                </div>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-4">Image à la une</h6>
                    
                    @if(isset($post) && $post->featured_image)
                        <div class="mb-3 rounded-3 overflow-hidden shadow-sm border">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-100 h-auto" alt="Aperçu">
                        </div>
                    @endif

                    <input type="file" name="featured_image" id="featured_image" class="form-control rounded-3 border-gray-200">
                    <p class="text-secondary x-small mt-2 mb-0">Format recommandé : 1200x800px. Max 2Mo.</p>
                </div>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-4">URL Vidéo</h6>
                    <div class="mb-0">
                        <label for="url_video" class="form-label small fw-bold text-secondary">Lien de la vidéo (Optionnel)</label>
                        <input type="url" name="url_video" id="url_video" class="form-control rounded-3 border-gray-200" value="{{ old('url_video', $post->url_video ?? '') }}" placeholder="https://www.youtube.com/watch?v=...">
                        <p class="text-secondary x-small mt-2 mb-0">Ajoutez un lien YouTube, Vimeo ou autre pour inclure une vidéo à l'article.</p>
                        @error('url_video') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3 py-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-save me-2"></i> {{ isset($post) ? 'Enregistrer les modifications' : 'Publier l\'article' }}
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush

<style>
    .ck-editor__editable {
        min-height: 400px;
        border-bottom-left-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
    }
    .ck-toolbar {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        background-color: #f9fafb !important;
        border-color: #e5e7eb !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #e5e7eb !important;
    }
    .x-small { font-size: 0.7rem; }
</style>
