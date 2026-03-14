@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Critiques & Avis</h1>
                <p class="text-secondary">Gérez les critiques qui s'affichent dans la colonne centrale de l'accueil.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-2"></i> Nouvelle Critique
                </a>
            </div>
        </div>
    </div>

    <x-data-table :headers="['Aperçu', 'Titre', 'Score', 'Statut', 'Actions']" :collection="$reviews">
        <x-slot name="title">Liste des critiques</x-slot>

        @foreach($reviews as $review)
            <tr class="group">
                <td class="px-6 py-4">
                    <div class="rounded-3 overflow-hidden shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 60px; height: 40px;">
                        @if($review->image)
                            <img src="{{ asset('storage/' . $review->image) }}" class="w-100 h-100 object-cover" alt="">
                        @else
                            <i class="fa-solid fa-star text-secondary"></i>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark">{{ $review->title }}</div>
                    <div class="text-xs text-secondary mt-1">
                        <i class="fa-solid fa-calendar me-1 opacity-50"></i> {{ $review->created_at->format('d/m/Y') }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="badge bg-primary text-white border-0 py-1 px-3 shadow-sm fw-bold" style="background-color: #dc143c !important;">{{ $review->score }}/10</span>
                </td>
                <td class="px-6 py-4">
                    @if($review->is_active)
                        <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold" style="background-color: #16a34a !important;">Actif</span>
                    @else
                        <span class="badge rounded-pill bg-warning text-white px-3 py-1 border-0 shadow-sm fw-bold" style="background-color: #f59e0b !important;">Inactif</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                                <li><a class="dropdown-item small py-2 rounded-2 fw-bold" href="{{ route('admin.reviews.edit', $review) }}"><i class="fa-solid fa-pen me-2 text-primary"></i>Modifier</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette critique ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold bg-danger-subtle">
                                            <i class="fa-solid fa-trash me-2"></i> SUPPRIMER
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection

<style>
    .text-primary { color: #dc143c !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
</style>
