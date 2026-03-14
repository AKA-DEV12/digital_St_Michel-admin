@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Articles du Blog</h1>
                <p class="text-secondary">Gérez les actualités et publications de la paroisse.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blog.categories') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                    <i class="fa-solid fa-tags me-2"></i> Catégories
                </a>
                <a href="{{ route('admin.blog.tags') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                    <i class="fa-solid fa-hashtag me-2"></i> Tags
                </a>
                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-2"></i> Nouvel Article
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-5 animate-fade-in g-4" style="animation-delay: 0.1s">
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white border-start border-4 border-crimson">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-newspaper fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Total Articles</h6>
                        <h3 class="fw-bold mb-0">{{ $posts->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white border-start border-4 border-success">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #f0fdf4; color: #16a34a;">
                        <i class="fa-solid fa-check-circle fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Publiés</h6>
                        <h3 class="fw-bold mb-0">{{ \App\Models\BlogPost::where('status', 'published')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white border-start border-4 border-warning">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #fffbeb; color: #d97706;">
                        <i class="fa-solid fa-clock fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Brouillons</h6>
                        <h3 class="fw-bold mb-0">{{ \App\Models\BlogPost::where('status', 'draft')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-data-table :headers="['Aperçu', 'Titre', 'Catégorie', 'Auteur', 'Statut', 'Actions']" :collection="$posts">
        <x-slot name="title">Liste des articles</x-slot>

        @foreach($posts as $post)
            <tr class="group">
                <td class="px-6 py-4">
                    <div class="rounded-3 overflow-hidden shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 60px; height: 40px;">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-100 h-100 object-cover" alt="">
                        @elseif($post->video_thumbnail)
                            <img src="{{ $post->video_thumbnail }}" class="w-100 h-100 object-cover" alt="">
                        @elseif($post->url_video)
                            <i class="fa-solid fa-video text-secondary"></i>
                        @else
                            <img src="https://images.unsplash.com/photo-1548625361-195fd09a2759?auto=format&fit=crop&q=80&w=100" class="w-100 h-100 object-cover" alt="">
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark">{{ $post->title }}</div>
                    <div class="text-xs text-secondary mt-1">
                        <i class="fa-solid fa-calendar me-1 opacity-50"></i> {{ $post->created_at->format('d/m/Y') }}
                        @if($post->is_featured)
                            <span class="ms-2 badge bg-crimson-600 text-white border-0 py-0.5 px-2" style="font-size: 9px;">À LA UNE</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="badge bg-light text-dark border">{{ $post->category->name ?? 'N/A' }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[8px] font-bold border">
                            {{ substr($post->author->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="small text-secondary fw-medium">{{ $post->author->name ?? 'Rédaction' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($post->status == 'published')
                        <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold" style="background-color: #16a34a !important;">Publié</span>
                    @else
                        <span class="badge rounded-pill bg-warning text-white px-3 py-1 border-0 shadow-sm fw-bold" style="background-color: #f59e0b !important;">Brouillon</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                                <li><a class="dropdown-item small py-2 rounded-2 fw-bold" href="{{ route('admin.blog.edit', $post) }}"><i class="fa-solid fa-pen me-2 text-primary"></i>Modifier</a></li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
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
    .border-crimson { border-color: #dc143c !important; }
    .bg-primary-light { background-color: #fff1f2; }
    .text-primary { color: #dc143c !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
</style>
