<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use App\Services\CloudinaryService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupMemberController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Méthode universelle pour récupérer le premier fichier valide depuis la request
     */
    public function getFileFromRequest(Request $request)
    {
        \Log::info('Recherche de fichiers dans la request (GroupMember)', [
            'all_files' => $request->files->all(),
            'request_all' => $request->all()
        ]);

        foreach ($request->files->all() as $key => $file) {
            if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
                \Log::info('Fichier trouvé (GroupMember)', ['key' => $key, 'filename' => $file->getClientOriginalName()]);
                return $file;
            }
        }

        \Log::warning('Aucun fichier trouvé dans la request (GroupMember)');
        return null;
    }
    /**
     * Export resources.
     */
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $search = $request->get('search');
        $query = GroupMember::query();

        // Appliquer les restrictions selon les rôles
        if (!auth()->user()->hasRole('Super Admin')) {
            // Si ce n'est pas un Super Admin, limiter aux membres de son groupe ou qu'il a créés
            $query->where(function ($q) {
                $q->whereHas('groups', function($q2) {
                    $q2->where('groups.id', auth()->user()->group_id);
                })->orWhere('created_by', auth()->id());
            });
        }

        // Pour pouvoir accéder aux champs du pivot dans l'export
        $query->with(['groups' => function($q) {
            if (auth()->user()->group_id) {
                $q->where('groups.id', auth()->user()->group_id);
            }
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_prenom', 'like', "%{$search}%")
                    ->orWhereHas('groups', function ($q2) use ($search) {
                        $q2->where('group_group_member.responsabilite', 'like', "%{$search}%");
                    });
            });
        }

        $members = $query->orderBy('nom_prenom')->get();

        return $exportService->export(
            $request,
            'Membres des Groupes',
            'group_members_' . date('Y-m-d'),
            ['ID', 'Nom & Prénom', 'Date Adhésion', 'Responsabilité', 'Situation Matrimoniale', 'Créé le'],
            $members,
            function ($member) {
                $pivot = $member->groups->first()?->pivot;
                $date_adhesion = $pivot && $pivot->date_adhesion ? \Carbon\Carbon::parse($pivot->date_adhesion)->format('d/m/Y') : '-';
                $responsabilite = $pivot ? $pivot->responsabilite : '-';

                return [
                    $member->id,
                    $member->nom_prenom,
                    $date_adhesion,
                    $responsabilite,
                    $member->situation_matrimoniale,
                    $member->created_at ? $member->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = GroupMember::query();

        // Appliquer les restrictions selon les rôles
        if (!auth()->user()->hasRole('Super Admin')) {
            // Si ce n'est pas un Super Admin, limiter aux membres de son groupe ou qu'il a créés
            $query->where(function ($q) {
                $q->whereHas('groups', function($q2) {
                    $q2->where('groups.id', auth()->user()->group_id);
                })->orWhere('created_by', auth()->id());
            });
        }

        // Pour pouvoir accéder aux champs du pivot
        $query->with(['groups' => function($q) {
            if (auth()->user()->group_id) {
                $q->where('groups.id', auth()->user()->group_id);
            }
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_prenom', 'like', "%{$search}%")
                    ->orWhereHas('groups', function ($q2) use ($search) {
                        $q2->where('group_group_member.responsabilite', 'like', "%{$search}%");
                    });
            });
        }

        $members = $query->orderBy('nom_prenom')->paginate(33)->withQueryString();

        // Attacher le pivot pour chaque membre afin que les vues puissent y accéder directement
        $members->getCollection()->transform(function ($member) {
            $member->pivot = $member->groups->first()?->pivot;
            return $member;
        });

        return view('admin.group-members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = \App\Models\Group::with('members')->get();
        return view('admin.group-members.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est associé à un groupe
        if (!auth()->user()->hasGroup()) {
            return redirect()->back()
                ->with('error', 'Vous devez être associé à un groupe pour ajouter un membre.');
        }

        // Si l'utilisateur a sélectionné un membre existant
        if ($request->has('existing_member_id') && !empty($request->existing_member_id)) {
            $existingMember = GroupMember::findOrFail($request->existing_member_id);
            $groupId = $request->group_id ?? auth()->user()->group_id;

            if ($existingMember->groups()->where('groups.id', $groupId)->exists()) {
                return redirect()->back()->with('error', 'Ce membre appartient déjà à ce groupe.');
            }

            // Validation de la date (requise pour la pivot)
            $request->validate([
                'date_adhesion' => 'required|date|before_or_equal:today',
                'responsabilite' => 'nullable|string|max:255',
            ]);

            $existingMember->groups()->attach($groupId, [
                'responsabilite' => $request->responsabilite,
                'date_adhesion' => $request->date_adhesion,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('group-members.index')
                ->with('success', 'Membre rattaché à votre groupe avec succès.');
        }

        $validated = $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_adhesion' => 'required|date|before_or_equal:today',
            'responsabilite' => 'nullable|string|max:255',
            'situation_professionnelle' => 'nullable|string|max:255',
            'nombre_enfant' => 'nullable|integer|min:0',
            'situation_matrimoniale' => 'required|in:Celibataire,Concubinage,Marier,Divorcer,Veuve / Veuf',
            'group_id' => 'nullable|exists:groups,id',
        ], [
            'nom_prenom.required' => 'Le nom et prénom sont obligatoires',
            'date_adhesion.before_or_equal' => 'La date d\'adhésion ne peut pas être dans le futur',
            'situation_matrimoniale.required' => 'La situation matrimoniale est obligatoire',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG ou JPG',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB',
            'nombre_enfant.integer' => 'Le nombre d\'enfants doit être un nombre entier',
        ]);

        // GESTION DE LA PHOTO (Webcam ou Fichier)
        if ($request->filled('captured_image')) {
            // Photo prise par webcam (Base64)
            \Log::info('Image webcam détectée (GroupMember)');
            $base64Image = $request->input('captured_image');
            
            $result = Cloudinary::upload($base64Image, [
                'folder' => 'group-members/photos',
                'resource_type' => 'image'
            ]);
            
            $url = $result->getSecurePath();
            if (!$url) throw new \Exception('Upload Cloudinary (Webcam) échoué');
            
            $validated['photo'] = $url;
            \Log::info('Upload Cloudinary réussi (Webcam)', ['url' => $url]);

        } elseif ($request->hasFile('photo')) {
            // Photo uploadée classiquement
            $file = $request->file('photo');
            
            if (!$file->isValid()) {
                throw new \Exception('Fichier invalide: ' . $file->getErrorMessage());
            }

            $realPath = $file->getRealPath();
            if (!$realPath) throw new \Exception('Chemin temporaire invalide');

            \Log::info('Fichier valide détecté (GroupMember)', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            $folder = str_contains($file->getMimeType(), 'video') ? 'group-members/videos' : 'group-members/photos';

            $result = Cloudinary::upload($realPath, [
                'folder' => $folder,
                'resource_type' => 'auto'
            ]);

            $url = $result->getSecurePath();
            if (!$url) throw new \Exception('Upload Cloudinary échoué');

            $validated['photo'] = $url;
            \Log::info('Upload Cloudinary réussi (Fichier)', ['url' => $url]);
        }

        // Nettoyage des champs pivot avant création du membre
        $groupId = $validated['group_id'] ?? auth()->user()->group_id;
        $validated['created_by'] = auth()->id();
        
        unset($validated['group_id']);
        unset($validated['date_adhesion']);
        unset($validated['responsabilite']);

        $member = GroupMember::create($validated);

        // Ajout dans la table pivot
        $member->groups()->attach($groupId, [
            'responsabilite' => $request->responsabilite,
            'date_adhesion' => $request->date_adhesion,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('group-members.index')
            ->with('success', 'Membre ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupMember $groupMember)
    {
        // Vérifier les permissions d'accès
        if (!auth()->user()->hasRole('Super Admin')) {
            // Si ce n'est pas un Super Admin, vérifier que le membre appartient à son groupe ou qu'il l'a créé
            $belongsToGroup = $groupMember->groups()->where('groups.id', auth()->user()->group_id)->exists();
            if (!$belongsToGroup && $groupMember->created_by !== auth()->id()) {
                abort(403, 'Vous n\'êtes pas autorisé à voir ce membre.');
            }
        }
        // Gérer le pivot pour la vue (si l'utilisateur n'a pas de groupe, on prend le premier groupe du membre)
        $userGroupId = auth()->user()->group_id;
        if ($userGroupId) {
            $pivot = $groupMember->groups()->where('groups.id', $userGroupId)->first()?->pivot;
        } else {
            $pivot = $groupMember->groups()->first()?->pivot;
        }
        $groupMember->pivot = $pivot; // Attach for the view

        return view('admin.group-members.show', compact('groupMember'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupMember $groupMember)
    {
        // Vérifier les permissions d'accès
        if (!auth()->user()->hasRole('Super Admin')) {
            $belongsToGroup = $groupMember->groups()->where('groups.id', auth()->user()->group_id)->exists();
            if (!$belongsToGroup && $groupMember->created_by !== auth()->id()) {
                abort(403, 'Vous n\'êtes pas autorisé à modifier ce membre.');
            }
        }
        // Gérer le pivot pour la vue
        $userGroupId = auth()->user()->group_id;
        if ($userGroupId) {
            $pivot = $groupMember->groups()->where('groups.id', $userGroupId)->first()?->pivot;
        } else {
            $pivot = $groupMember->groups()->first()?->pivot;
        }
        $groupMember->pivot = $pivot; // Attach for the view

        return view('admin.group-members.edit', compact('groupMember'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupMember $groupMember)
    {
        // Vérifier les permissions d'accès
        if (!auth()->user()->hasRole('Super Admin')) {
            $belongsToGroup = $groupMember->groups()->where('groups.id', auth()->user()->group_id)->exists();
            if (!$belongsToGroup && $groupMember->created_by !== auth()->id()) {
                abort(403, 'Vous n\'êtes pas autorisé à modifier ce membre.');
            }
        }

        $validated = $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'date_adhesion' => 'required|date|before_or_equal:today',
            'responsabilite' => 'nullable|string|max:255',
            'situation_professionnelle' => 'nullable|string|max:255',
            'nombre_enfant' => 'nullable|integer|min:0',
            'situation_matrimoniale' => 'required|in:Celibataire,Concubinage,Marier,Divorcer,Veuve / Veuf',
        ], [
            'nom_prenom.required' => 'Le nom et prénom sont obligatoires',
            'date_adhesion.before_or_equal' => 'La date d\'adhésion ne peut pas être dans le futur',
            'situation_matrimoniale.required' => 'La situation matrimoniale est obligatoire',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG ou JPG',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB',
            'nombre_enfant.integer' => 'Le nombre d\'enfants doit être un nombre entier',
        ]);

        // GESTION DE LA PHOTO (Webcam ou Fichier)
        if ($request->filled('captured_image')) {
            // Photo prise par webcam (Base64)
            \Log::info('Image webcam détectée (GroupMember update)');
            $base64Image = $request->input('captured_image');
            
            $result = Cloudinary::upload($base64Image, [
                'folder' => 'group-members/photos',
                'resource_type' => 'image'
            ]);
            
            $url = $result->getSecurePath();
            if (!$url) throw new \Exception('Upload Cloudinary (Webcam) échoué');
            
            $validated['photo'] = $url;
            \Log::info('Upload Cloudinary réussi (Webcam)', ['url' => $url]);

        } elseif ($request->hasFile('photo')) {
            // Photo uploadée classiquement
            $file = $request->file('photo');
            
            if (!$file->isValid()) {
                throw new \Exception('Fichier invalide: ' . $file->getErrorMessage());
            }

            $realPath = $file->getRealPath();
            if (!$realPath) throw new \Exception('Chemin temporaire invalide');

            \Log::info('Fichier valide détecté (GroupMember update)', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            $folder = str_contains($file->getMimeType(), 'video') ? 'group-members/videos' : 'group-members/photos';

            $result = Cloudinary::upload($realPath, [
                'folder' => $folder,
                'resource_type' => 'auto'
            ]);

            $url = $result->getSecurePath();
            if (!$url) throw new \Exception('Upload Cloudinary échoué');

            $validated['photo'] = $url;
            \Log::info('Upload Cloudinary réussi (Fichier)', ['url' => $url]);
        } else {
            \Log::info('Aucun fichier ou photo webcam trouvé pour la mise à jour du membre du groupe');
        }

        // Update the member's core fields
        unset($validated['date_adhesion'], $validated['responsabilite']);
        $groupMember->update($validated);
        
        // Update pivot data
        $groupId = auth()->user()->group_id;
        if ($groupMember->groups()->where('groups.id', $groupId)->exists()) {
            $groupMember->groups()->updateExistingPivot($groupId, [
                'responsabilite' => $request->responsabilite,
                'date_adhesion' => $request->date_adhesion,
            ]);
        }

        return redirect()->route('group-members.index')
            ->with('success', 'Membre mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupMember $groupMember)
    {
        // Vérifier les permissions d'accès
        if (!auth()->user()->hasRole('Super Admin')) {
            $belongsToGroup = $groupMember->groups()->where('groups.id', auth()->user()->group_id)->exists();
            if (!$belongsToGroup && $groupMember->created_by !== auth()->id()) {
                abort(403, 'Vous n\'êtes pas autorisé à supprimer ce membre.');
            }
        }

        // Au lieu de supprimer le membre (qui pourrait appartenir à d'autres groupes), 
        // on le détache du groupe courant. 
        $groupId = auth()->user()->group_id;
        $groupMember->groups()->detach($groupId);
        
        // S'il n'appartient plus à aucun groupe, on pourrait le supprimer complètement
        if ($groupMember->groups()->count() == 0) {
            $groupMember->delete();
        }

        return redirect()->route('group-members.index')
            ->with('success', 'Membre retiré du groupe avec succès.');
    }
}
