@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Tags</h1>
                <p class="text-secondary">Gérez les étiquettes pour un meilleur référencement.</p>
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
                <h5 class="fw-bold mb-4">Nouveau Tag</h5>
                <form action="{{ route('admin.blog.tags.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label small fw-bold text-secondary">Nom du tag</label>
                        <input type="text" name="name" id="name" class="form-control rounded-3 py-2 border-gray-200" required placeholder="Ex: Info">
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">
                        <i class="fa-solid fa-plus me-2"></i> Ajouter le tag
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-12 col-lg-8">
            <x-data-table :headers="['Nom', 'Articles', 'Slug', 'Actions']" :collection="$tags">
                <x-slot name="title">Liste des tags</x-slot>

                @foreach($tags as $tag)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-hashtag text-secondary opacity-50"></i>
                                <span class="fw-bold text-dark">{{ $tag->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge bg-light text-dark border-0 rounded-pill px-3 py-1 fw-bold">{{ $tag->posts_count }} articles</span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs text-secondary bg-gray-50 px-2 py-1 rounded">{{ $tag->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-end">
                             <form action="{{ route('admin.blog.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Confirmer la suppression du tag ?');">
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
