<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:12288',
            'header_ad_flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:12288',
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'site_address' => 'nullable|string|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_email' => 'nullable|email|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'ad_digital_service_title' => 'nullable|string|max:255',
            'ad_digital_service_text' => 'nullable|string|max:255',
            'ad_digital_service_link' => 'nullable|url|max:255',
            'ad_digital_service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:12288',
        ];

        $messages = [
            'site_logo.max' => 'Le logo doit faire moins de 12 Mo',
            'header_ad_flyer.max' => 'La bannière doit faire moins de 12 Mo',
            'ad_digital_service_image.max' => 'L\'image doit faire moins de 12 Mo',
            'site_logo.image' => 'Le fichier doit être une image',
            'header_ad_flyer.image' => 'Le fichier doit être une image',
            'ad_digital_service_image.image' => 'Le fichier doit être une image',
            'site_email.email' => 'L\'adresse e-mail doit être valide',
            'facebook_url.url' => 'Le lien Facebook doit être une URL valide',
            'youtube_url.url' => 'Le lien YouTube doit être une URL valide',
            'tiktok_url.url' => 'Le lien TikTok doit être une URL valide',
            'ad_digital_service_link.url' => 'Le lien de la publicité doit être une URL valide',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle File uploads
            foreach (['site_logo', 'header_ad_flyer', 'ad_digital_service_image'] as $key) {
                if ($request->hasFile($key)) {
                    // Delete old file if exists
                    $oldSetting = SiteSetting::where('key', $key)->first();
                    if ($oldSetting && $oldSetting->value) {
                        Storage::disk('public')->delete($oldSetting->value);
                    }

                    $path = $request->file($key)->store('settings', 'public');
                    SiteSetting::updateOrCreate(['key' => $key], ['value' => $path]);
                }
            }

            // Handle Text fields
            $textFields = [
                'site_name', 'site_description', 'site_address', 'site_phone', 'site_email',
                'facebook_url', 'youtube_url', 'tiktok_url',
                'ad_digital_service_title', 'ad_digital_service_text', 'ad_digital_service_link'
            ];

            foreach ($textFields as $field) {
                if ($request->has($field)) {
                    SiteSetting::updateOrCreate(['key' => $field], ['value' => $request->get($field)]);
                }
            }

            return redirect()->back()->with('success', 'Paramètres mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage())
                ->withInput();
        }
    }
}
