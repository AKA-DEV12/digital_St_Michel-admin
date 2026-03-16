@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Espace Publicitaire</h1>
                <p class="text-secondary">Gérez les publicités affichées dans le slider de la barre latérale.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.ads.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-2"></i> Nouvelle Publicité
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-5 animate-fade-in g-4" style="animation-delay: 0.1s">
        <div class="col-12 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white border-start border-4 border-crimson">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-rectangle-ad fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Total Publicités</h6>
                        <h3 class="fw-bold mb-0">{{ $ads->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white border-start border-4 border-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #f0fdf4; color: #16a34a;">
                        <i class="fa-solid fa-eye fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Publicités Actives</h6>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Advertisement::where('is_active', true)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-data-table :headers="['Aperçu', 'Titre', 'Lien', 'Ordre', 'Statut', 'Actions']" :collection="$ads">
        <x-slot name="title">Liste des publicités</x-slot>

        @foreach($ads as $ad)
            <tr class="group">
                <td class="px-6 py-4">
                    <div class="rounded-3 overflow-hidden shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 80px; height: 50px;">
                        @if($ad->image)
                            <img src="{{ asset('storage/' . $ad->image) }}" class="w-100 h-100 object-cover" alt="">
                        @else
                            <i class="fa-solid fa-image text-secondary opacity-30"></i>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark">{{ $ad->title }}</div>
                    <div class="text-xs text-secondary mt-1">
                        <i class="fa-solid fa-calendar me-1 opacity-50"></i> Créé le {{ $ad->created_at->format('d/m/Y') }}
                    </div>
                </td>
                <td class="px-6 py-4 text-secondary small">
                    <span class="text-truncate d-inline-block" style="max-width: 150px;">
                        {{ $ad->link_url ?: 'Aucun lien' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="badge bg-light text-dark border">{{ $ad->order }}</span>
                </td>
                <td class="px-6 py-4">
                    @if($ad->is_active)
                        <span class="badge bg-success-light text-success border border-success-subtle px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-circle-check me-1"></i> Actif
                        </span>
                    @else
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-circle-xmark me-1"></i> Inactif
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-white btn-sm border shadow-sm rounded-3 px-3">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" onsubmit="return confirm('Supprimer cette publicité ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-white btn-sm border shadow-sm rounded-3 px-3 text-danger">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
