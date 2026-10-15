<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    /**
     * Display the settings management view grouped by tabs.
     */
    public function index(Request $request): View
    {
        $settings = SiteSetting::orderBy('sort_order')->orderBy('id')->get();
        $groupedSettings = $settings->groupBy('group');

        $activeTab = $request->query('tab', 'identity');

        $groups = [
            'identity' => [
                'name' => 'Identitas',
                'icon' => 'user',
                'desc' => 'Nama lengkap, panggilan, dan deskripsi peran utama.',
            ],
            'hero' => [
                'name' => 'Hero Section',
                'icon' => 'sparkles',
                'desc' => 'Banner beranda, judul utama, pengantar, dan foto hero.',
            ],
            'about' => [
                'name' => 'Tentang Saya',
                'icon' => 'file-text',
                'desc' => 'Biografi ringkas, paragraf narasi, dan foto profil.',
            ],
            'services' => [
                'name' => 'Layanan & Keahlian',
                'icon' => 'code',
                'desc' => 'Bidang fokus, keahlian utama, dan poin rincian.',
            ],
            'technologies' => [
                'name' => 'Teknologi',
                'icon' => 'cpu',
                'desc' => 'Daftar stack bahasa, framework, dan software pendukung.',
            ],
            'social' => [
                'name' => 'Kontak & Tautan',
                'icon' => 'link',
                'desc' => 'Email, WhatsApp, akun jejaring sosial, dan file CV.',
            ],
            'gallery' => [
                'name' => 'Galeri & Visual',
                'icon' => 'image',
                'desc' => 'Foto dokumentasi, dokumentasi aktivitas, dan preview visual.',
            ],
        ];

        return view('admin.settings.index', [
            'groupedSettings' => $groupedSettings,
            'groups' => $groups,
            'activeTab' => $activeTab,
            'totalSettings' => $settings->count(),
        ]);
    }

    /**
     * Update all submitted settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $inputSettings = $request->input('settings', []);
        $files = $request->file('settings_files', []);

        $activeTab = $request->input('active_tab', 'identity');

        // Fetch all target settings
        $allSettings = SiteSetting::all()->keyBy('key');

        foreach ($inputSettings as $key => $value) {
            $setting = $allSettings->get($key) ?? SiteSetting::firstOrNew(['key' => $key]);

            if (! $setting->exists) {
                $group = Str::before($key, '.');
                if (in_array($group, ['contact', 'portfolio'])) {
                    $group = 'social';
                }
                $setting->group = $group;
                $setting->label = Str::headline(str_replace('.', ' ', $key));
                $setting->type = 'text';
            }

            // Validate based on type
            if ($setting->type === 'json') {
                // If it's sent as a raw string or array
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } elseif (is_string($value)) {
                    $value = trim($value);
                    if ($value !== '' && ! json_validate($value)) {
                        return back()
                            ->withInput()
                            ->withErrors(["settings.{$key}" => "Format JSON untuk {$setting->label} tidak valid."]);
                    }
                }
            }

            $setting->value = $value;
            $setting->save();
        }

        // Process uploaded image files if any
        if (is_array($files)) {
            foreach ($files as $key => $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                $setting = $allSettings->get($key);
                if (! $setting) {
                    continue;
                }

                $request->validate([
                    "settings_files.{$key}" => ['image', 'max:5120'], // 5MB
                ]);

                // Delete previous uploaded file if in public storage
                if ($setting->value && str_starts_with($setting->value, 'storage/settings/')) {
                    $oldRelative = substr($setting->value, strlen('storage/'));
                    Storage::disk('public')->delete($oldRelative);
                }

                $path = 'storage/'.$file->store('settings', 'public');
                $setting->update(['value' => $path]);
            }
        }

        Cache::forget('site_settings_map');

        return redirect()
            ->route('admin.settings.index', ['tab' => $activeTab])
            ->with('status', 'Pengaturan konten berhasil disimpan.');
    }

    /**
     * Upload an individual setting image (e.g. via async / AJAX).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'key' => ['required', 'string'],
            'image' => ['required', 'image', 'max:5120'],
        ]);

        $key = $request->string('key')->value();
        $setting = SiteSetting::where('key', $key)->firstOrFail();

        $file = $request->file('image');

        if ($setting->value && str_starts_with($setting->value, 'storage/settings/')) {
            $oldRelative = substr($setting->value, strlen('storage/'));
            Storage::disk('public')->delete($oldRelative);
        }

        $path = 'storage/'.$file->store('settings', 'public');
        $setting->update(['value' => $path]);
        Cache::forget('site_settings_map');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset($path),
            'message' => 'Gambar berhasil diperbarui.',
        ]);
    }

    /**
     * Upload an image specifically for the visual gallery slots.
     */
    public function uploadGalleryImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'], // 5MB
        ]);

        $file = $request->file('image');
        $path = 'storage/'.$file->store('gallery', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset($path),
            'filename' => basename($path),
            'message' => 'Gambar galeri berhasil diunggah.',
        ]);
    }
}
