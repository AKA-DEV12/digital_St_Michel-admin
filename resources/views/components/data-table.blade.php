@props(['headers', 'collection'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
    <!-- DataTable Header -->
    <div class="px-6 py-4 border-b border-gray-50 bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <h2 class="h5 fw-bold text-dark mb-0">{{ $title ?? 'Liste' }}</h2>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm border-gray-200 rounded-3" style="width: 70px;">
                        <option value="7">7</option>
                        <option value="15">15</option>
                        <option value="33" selected>33</option>
                    </select>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2">
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="position-relative" style="width: 240px;">
                        <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm ps-5 border-gray-200 rounded-3 py-2" placeholder="Rechercher...">
                    </div>
                </form>

                @if(isset($advancedSearch))
                    {{ $advancedSearch }}
                @else
                    <button class="btn btn-sm btn-white border-gray-200 rounded-3 px-3 py-2 text-primary fw-600" data-bs-toggle="collapse" data-bs-target="#advancedSearchCollapse">
                        <i class="fa-solid fa-filter me-2"></i> Recherche avancée
                    </button>
                @endif

                <div class="dropdown">
                    <button class="btn btn-sm btn-white border-gray-200 rounded-3 px-3 py-2 text-secondary fw-600 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-file-export me-2"></i> Exporter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                        <li>
                            <form action="{{ url()->current() . '/export' }}" method="GET">
                                <input type="hidden" name="format" value="csv">
                                @foreach(request()->all() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <button type="submit" class="dropdown-item small py-2">
                                    <i class="fa-solid fa-file-csv me-2 text-info"></i> CSV
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                @if(isset($actions))
                    {{ $actions }}
                @endif
            </div>
        </div>
    </div>

    <!-- Advanced Search Collapse -->
    <div class="collapse border-b border-gray-50 bg-gray-50/30" id="advancedSearchCollapse">
        <div class="px-6 py-4">
            @if(isset($advancedSearch))
                {{ $advancedSearch }}
            @else
                <form action="{{ url()->current() }}" method="GET">
                    @foreach(request()->except(['date_from', 'date_to', 'status', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Date début</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm rounded-3">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Date fin</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm rounded-3">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3 py-2 fw-bold">
                                <i class="fa-solid fa-magnifying-glass me-2"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Table Body -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse table-hover">
            <thead>
                <tr class="bg-gray-50/50">
                    @foreach($headers as $header)
                        <th class="px-6 py-4 border-b border-gray-100">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $header }}</span>
                                <div class="d-flex flex-column" style="font-size: 0.5rem; line-height: 1;">
                                    <i class="fa-solid fa-caret-up text-gray-300"></i>
                                    <i class="fa-solid fa-caret-down text-gray-300"></i>
                                </div>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if($collection->count() === 0)
        <div class="p-10 text-center">
            <div class="bg-gray-50 rounded-2xl p-8 d-inline-block">
                <i class="fa-solid fa-inbox text-gray-200 fs-1 mb-3"></i>
                <p class="text-gray-400 mb-0">Aucun enregistrement trouvé</p>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="px-6 py-4 bg-white border-t border-gray-50">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-secondary small">
                Affichage de {{ $collection->firstItem() ?? 0 }} à {{ $collection->lastItem() ?? 0 }} sur {{ $collection->total() }} entrées
            </div>
            
            <div class="premium-pagination">
                {{ $collection->links('components.data-table-pagination') }}
            </div>
        </div>
    </div>
</div>

<style>
    .btn-white {
        background: white;
        transition: all 0.2s ease;
    }
    .btn-white:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    .text-gray-400 { color: #9ca3af; }
    .text-gray-500 { color: #6b7280; }
    .bg-gray-50 { background-color: #f9fafb; }
    
    /* Hover highlight matching screenshot row lines */
    tbody tr {
        transition: background-color 0.2s ease;
    }
    tbody tr:hover {
        background-color: #fcfdfe;
    }
</style>
