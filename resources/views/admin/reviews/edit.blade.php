@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Modifier la Critique</h1>
                <p class="text-secondary">Modifiez les informations de la critique.</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" enctype="multipart/form-data" class="animate-fade-in" style="animation-delay: 0.1s">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 overflow-hidden bg-white">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase">Titre de la critique</label>
                        <input type="text" name="title" class="form-control rounded-3 border-gray-100 py-3 px-4 @error('title') is-invalid @enderror" placeholder="Ex: At vero eos et ccusamus" value="{{ old('title', $review->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase">Note / Score (sur 10)</label>
                        <input type="number" step="0.1" name="score" class="form-control rounded-3 border-gray-100 py-3 px-4 @error('score') is-invalid @enderror" placeholder="Ex: 8.4" value="{{ old('score', $review->score) }}" required>
                        @error('score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <div class="form-check form-switch custom-switch py-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $review->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark ms-2" for="is_active">Activer cette critique</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase mb-3">Image de la critique</label>
                    <div class="mb-4">
                        <div class="image-upload-wrapper text-center p-4 border-2 border-dashed border-gray-100 rounded-4 bg-light transition-all hover-bg-light-2">
                            <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                            <label for="imageInput" class="cursor-pointer">
                                @if($review->image)
                                    <img src="{{ asset('storage/' . $review->image) }}" class="w-100 h-auto rounded-3 mb-3 shadow-sm border" id="previewImg">
                                @else
                                    <i class="fa-solid fa-cloud-arrow-up fs-1 text-secondary opacity-50 mb-3"></i>
                                    <p class="small text-secondary fw-medium mb-0">Cliquez pour modifier l'image</p>
                                @endif
                                <p class="text-xs text-secondary opacity-50 mt-1">PNG, JPG jusqu'à 2MB</p>
                            </label>
                        </div>
                        <div id="imagePreview" class="mt-3 rounded-3 overflow-hidden d-none">
                            <img src="" class="w-100 h-auto" id="previewImgDynamic">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-3 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-save"></i> METTRE À JOUR
                    </button>
                    <button type="button" class="btn btn-light rounded-3 py-3 fw-bold text-danger border border-gray-100 h-12 flex items-center justify-center" onclick="if(confirm('Supprimer cette critique ?')) document.getElementById('delete-form').submit();">
                        <i class="fa-solid fa-trash me-2"></i> SUPPRIMER
                    </button>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" id="delete-form" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('previewImg') || document.getElementById('previewImgDynamic');
                previewImg.src = e.target.result;
                if (document.getElementById('imagePreview')) {
                    document.getElementById('imagePreview').classList.remove('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
