@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Catégories</h1>
                <p class="text-secondary">Organisez vos articles par thématiques.</p>
            </div>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Retour aux articles
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white">
                <h5 class="fw-bold mb-4">Nouvelle Catégorie</h5>
                <form action="{{ route('admin.blog.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label small fw-bold text-secondary">Nom de la catégorie</label>
                        <input type="text" name="name" id="name" class="form-control rounded-3 py-2 border-gray-200" required placeholder="Ex: Spiritualité">
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label small fw-bold text-secondary">Description (Optionnel)</label>
                        <textarea name="description" id="description" rows="3" class="form-control rounded-3 border-gray-200" placeholder="Brève description..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">
                        <i class="fa-solid fa-plus me-2"></i> Ajouter la catégorie
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-12 col-lg-8">
            <x-data-table :headers="['Nom', 'Articles', 'Slug', 'Actions']" :collection="$categories">
                <x-slot name="title">Liste des thématiques</x-slot>

                @foreach($categories as $category)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <div class="fw-bold text-dark">{{ $category->name }}</div>
                            <div class="text-xs text-secondary mt-1 line-clamp-1">{{ $category->description ?? 'Pas de description' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge bg-primary-light text-primary border-0 rounded-pill px-3 py-1 fw-bold">{{ $category->posts_count }} articles</span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs text-secondary bg-gray-50 px-2 py-1 rounded">{{ $category->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-end">
                             <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Attention: Tous les articles de cette catégorie seront orphelins. Confirmer ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-white border border-gray-100 text-danger rounded-3 px-3 py-2 shadow-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>
        </div>
    </div>
@endsection

<style>
    .bg-primary-light { background-color: #fff1f2; }
    .text-primary { color: #dc143c !important; }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
