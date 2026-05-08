<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\CloudinaryService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
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
        \Log::info('Recherche de fichiers dans la request (Advertisement)', [
            'all_files' => $request->files->all(),
            'request_all' => $request->all()
        ]);

        foreach ($request->files->all() as $key => $file) {
            if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
                \Log::info('Fichier trouvé (Advertisement)', ['key' => $key, 'filename' => $file->getClientOriginalName()]);
                return $file;
            }
        }

        \Log::warning('Aucun fichier trouvé dans la request (Advertisement)');
        return null;
    }
    public function index()
    {
        $ads = Advertisement::orderBy('order')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'image' => 'required|image|max:12288', // 12Mo max as requested
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        // GESTION OBLIGATOIRE avec appel direct à Cloudinary
        $file = $request->file('image');
        
        if (!$file) {
            throw new \Exception('Fichier non reçu');
        } else {
            // Validation du fichier
            if (!$file->isValid()) {
                throw new \Exception('Fichier invalide: ' . $file->getErrorMessage());
            }

            \Log::info('Fichier valide détecté (Advertisement)', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Utilisation de CloudinaryService
            $url = $this->cloudinaryService->uploadFile($file, 'ads');
            
            if (!$url) {
                throw new \Exception('Upload Cloudinary échoué');
            }

            \Log::info('Upload Cloudinary réussi (Advertisement)', ['url' => $url]);
            $validated['image'] = $url;
        }

        Advertisement::create($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Publicité créée avec succès.');
    }

    public function edit(Advertisement $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, Advertisement $ad)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|max:12288',
            'link_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        // GESTION OBLIGATOIRE avec appel direct à Cloudinary
        $file = $request->file('image');
        
        if (!$file) {
            \Log::info('Aucun fichier trouvé pour la mise à jour de la publicité');
        } else {
            // Validation du fichier
            if (!$file->isValid()) {
                throw new \Exception('Fichier invalide: ' . $file->getErrorMessage());
            }

            \Log::info('Fichier valide détecté (Advertisement update)', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // Utilisation de CloudinaryService
            $url = $this->cloudinaryService->uploadFile($file, 'ads');
            
            if (!$url) {
                throw new \Exception('Upload Cloudinary échoué');
            }

            \Log::info('Upload Cloudinary réussi (Advertisement update)', ['url' => $url]);
            $validated['image'] = $url;
        }

        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', 'Publicité mise à jour avec succès.');
    }

    public function destroy(Advertisement $ad)
    {
        // No local storage cleanup as per requirements
        // If we want to delete from Cloudinary, we need the public_id.
        // I will skip deletion for now to avoid complexity without public_id storage,
        // or I can try to extract public_id from URL if needed.
        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Publicité supprimée avec succès.');
    }
}
