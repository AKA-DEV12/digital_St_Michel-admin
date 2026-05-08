<?php

namespace App\Http\Controllers;

use App\Models\Catechist;
use App\Models\Group;
use App\Models\GroupMember;
use App\Services\CloudinaryService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CatechistController extends Controller
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
        \Log::info('Recherche de fichiers dans la request', [
            'all_files' => $request->files->all(),
            'request_all' => $request->all()
        ]);

        foreach ($request->files->all() as $key => $file) {
            if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
                \Log::info('Fichier trouvé', ['key' => $key, 'filename' => $file->getClientOriginalName()]);
                return $file;
            }
        }

        \Log::warning('Aucun fichier trouvé dans la request');
        return null;
    }
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'annee_catechese', 'statut_catechese']);
        
        $catechists = Catechist::with(['group', 'member'])
            ->filter($filters)
            ->latest()
            ->paginate(15);

        return view('admin.catechists.index', compact('catechists', 'filters'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('admin.catechists.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'antecedent' => $request->has('antecedent'),
            'groupe_mouvement' => $request->has('groupe_mouvement'),
            'baptiser' => $request->has('baptiser'),
        ]);

        $validated = $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lieu_habitation' => 'nullable|string|max:255',
            'situation_matrimoniale' => 'nullable|in:Celibataire,Concubinage,Marier,Divorcer,Veuve / Veuf',
            'nombre_enfant' => 'nullable|integer|min:0',
            'antecedent' => 'boolean',
            'antecedent_date' => 'nullable|date|required_if:antecedent,true',
            'antecedent_annee_catechese' => 'nullable|string|required_if:antecedent,true',
            'antecedent_paroisse' => 'nullable|string|required_if:antecedent,true',
            'groupe_mouvement' => 'boolean',
            'group_id' => 'nullable|exists:groups,id|required_if:groupe_mouvement,true',
            'member_id' => 'nullable|exists:group_members,id|required_if:groupe_mouvement,true',
            'situation_professionnelle' => 'nullable|string|max:255',
            'baptiser' => 'boolean',
            'date_bapteme' => 'nullable|date|required_if:baptiser,true',
            'paroisse_bapteme' => 'nullable|string|required_if:baptiser,true',
            'carnet_bapteme' => 'nullable|string|required_if:baptiser,true',
            'annee_catechese' => 'required|string',
            'statut_catechese' => 'required|in:En cours,Terminee',
        ]);

        // GESTION OBLIGATOIRE avec appel direct à Cloudinary
        $file = $request->file('photo');
        
        if (!$file) {
            // Vérifier si c'est une image capturée (base64)
            if ($request->filled('captured_image')) {
                \Log::info('Image capturée détectée', ['captured_image_length' => strlen($request->input('captured_image'))]);
                $validated['photo'] = $this->cloudinaryService->uploadFile($request->input('captured_image'), 'catechists/photos');
            } else {
                throw new \Exception('Fichier non reçu');
            }
        } else {
            // Validation du fichier
            if (!$file->isValid()) {
                throw new \Exception('Fichier invalide: ' . $file->getErrorMessage());
            }

            // Récupération du chemin réel OBLIGATOIRE
            $realPath = $file->getRealPath();
            
            if (!$realPath) {
                throw new \Exception('Chemin temporaire invalide');
            }

            \Log::info('Fichier valide détecté', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'real_path' => $realPath
            ]);

            // Détermination du dossier selon le type de fichier
            $folder = str_contains($file->getMimeType(), 'video') ? 'catechists/videos' : 'catechists/photos';
            
            // APPEL DIRECT à l'API Cloudinary qui fonctionne
            $result = Cloudinary::upload($realPath, [
                'folder' => $folder,
                'resource_type' => 'auto'
            ]);

            // Récupération de l'URL sécurisée
            $url = $result->getSecurePath();
            
            // Debug obligatoire
            //dd($realPath, $result, $url);
            
            if (!$url) {
                throw new \Exception('Upload Cloudinary échoué');
            }

            \Log::info('Upload Cloudinary réussi', ['url' => $url]);
            $validated['photo'] = $url;
        }

        $validated['created_by'] = auth()->id();
        $validated['antecedent'] = $request->has('antecedent');
        $validated['groupe_mouvement'] = $request->has('groupe_mouvement');
        $validated['baptiser'] = $request->has('baptiser');

        Catechist::create($validated);

        return redirect()->route('admin.catechists.index')
            ->with('success', 'Catéchiste ajouté avec succès.');
    }

    public function show(Catechist $catechist)
    {
        $catechist->load(['group', 'member', 'creator']);
        return view('admin.catechists.show', compact('catechist'));
    }

    public function edit(Catechist $catechist)
    {
        $groups = Group::all();
        return view('admin.catechists.edit', compact('catechist', 'groups'));
    }

    public function update(Request $request, Catechist $catechist)
    {
        $request->merge([
            'antecedent' => $request->has('antecedent'),
            'groupe_mouvement' => $request->has('groupe_mouvement'),
            'baptiser' => $request->has('baptiser'),
        ]);

        $validated = $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lieu_habitation' => 'nullable|string|max:255',
            'situation_matrimoniale' => 'nullable|in:Celibataire,Concubinage,Marier,Divorcer,Veuve / Veuf',
            'nombre_enfant' => 'nullable|integer|min:0',
            'antecedent' => 'boolean',
            'antecedent_date' => 'nullable|date|required_if:antecedent,true',
            'antecedent_annee_catechese' => 'nullable|string|required_if:antecedent,true',
            'antecedent_paroisse' => 'nullable|string|required_if:antecedent,true',
            'groupe_mouvement' => 'boolean',
            'group_id' => 'nullable|exists:groups,id|required_if:groupe_mouvement,true',
            'member_id' => 'nullable|exists:group_members,id|required_if:groupe_mouvement,true',
            'situation_professionnelle' => 'nullable|string|max:255',
            'baptiser' => 'boolean',
            'date_bapteme' => 'nullable|date|required_if:baptiser,true',
            'paroisse_bapteme' => 'nullable|string|required_if:baptiser,true',
            'carnet_bapteme' => 'nullable|string|required_if:baptiser,true',
            'annee_catechese' => 'required|string',
            'statut_catechese' => 'required|in:En cours,Terminee',
        ]);

        // GESTION OBLIGATOIRE avec appel direct à Cloudinary
        $file = $request->file('photo');
        
        if (!$file) {
            // Vérifier si c'est une image capturée (base64)
            if ($request->filled('captured_image')) {
                \Log::info('Image capturée détectée (update)', ['captured_image_length' => strlen($request->input('captured_image'))]);
                $validated['photo'] = $this->cloudinaryService->uploadFile($request->input('captured_image'), 'catechists/photos');
            } else {
                \Log::info('Aucun fichier ou image capturée trouvé (update)');
            }
        } else {
            // Validation du fichier
            if (!$file->isValid()) {
                throw new \Exception('Fichier invalide: ' . $file->getErrorMessage());
            }

            // Récupération du chemin réel OBLIGATOIRE
            $realPath = $file->getRealPath();
            
            if (!$realPath) {
                throw new \Exception('Chemin temporaire invalide');
            }

            \Log::info('Fichier valide détecté (update)', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'real_path' => $realPath
            ]);

            // Détermination du dossier selon le type de fichier
            $folder = str_contains($file->getMimeType(), 'video') ? 'catechists/videos' : 'catechists/photos';
            
            // APPEL DIRECT à l'API Cloudinary qui fonctionne
            $result = Cloudinary::upload($realPath, [
                'folder' => $folder,
                'resource_type' => 'auto'
            ]);

            // Récupération de l'URL sécurisée
            $url = $result->getSecurePath();
            
            if (!$url) {
                throw new \Exception('Upload Cloudinary échoué');
            }

            \Log::info('Upload Cloudinary réussi (update)', ['url' => $url]);
            $validated['photo'] = $url;
        }

        $validated['antecedent'] = $request->has('antecedent');
        $validated['groupe_mouvement'] = $request->has('groupe_mouvement');
        $validated['baptiser'] = $request->has('baptiser');

        $catechist->update($validated);

        return redirect()->route('admin.catechists.index')
            ->with('success', 'Catéchiste mis à jour avec succès.');
    }

    public function destroy(Catechist $catechist)
    {
        // Deletion from Cloudinary is skipped here as we only store the URL, 
        // but we ensure no local storage is touched.
        
        $catechist->delete();

        return redirect()->route('admin.catechists.index')
            ->with('success', 'Catéchiste supprimé avec succès.');
    }

    public function getMembersByGroup($groupId)
    {
        try {
            $members = GroupMember::where('group_id', $groupId)
                ->select('id', 'nom_prenom')
                ->get()
                ->map(function ($member) {
                    $parts = explode(' ', $member->nom_prenom, 2);
                    return [
                        'id' => $member->id,
                        'nom' => $parts[0] ?? '',
                        'prenom' => $parts[1] ?? ''
                    ];
                });
            
            return response()->json($members);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load members'], 500);
        }
    }
}
