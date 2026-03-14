@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Messages Flash</h1>
                <p class="text-secondary">Gérez les messages défilants du haut de page.</p>
            </div>
            <div>
                <a href="{{ route('admin.flash-messages.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-2"></i> Nouveau Message
                </a>
            </div>
        </div>
    </div>

    <x-data-table :headers="['ID', 'Message', 'Statut', 'Actions']" :collection="$messages">
        <x-slot name="title">Liste des messages flash</x-slot>

        @foreach($messages as $message)
            <tr class="group">
                <td class="px-6 py-4">{{ $message->id }}</td>
                <td class="px-6 py-4">
                    <div class="fw-medium text-dark">{{ Str::limit($message->message, 80) }}</div>
                </td>
                <td class="px-6 py-4">
                    @if($message->is_active)
                        <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold">Actif</span>
                    @else
                        <span class="badge rounded-pill bg-secondary text-white px-3 py-1 border-0 shadow-sm fw-bold">Inactif</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.flash-messages.edit', $message->id) }}" class="btn btn-sm btn-outline-primary rounded-3">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.flash-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Supprimer ce message ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
坊
