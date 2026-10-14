<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Support\ImageUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(): View
    {
        return view('admin.certificates.index', ['items' => Certificate::orderBy('sort_order')->orderByDesc('issued_at')->get()]);
    }

    public function create(): View
    {
        return view('admin.certificates.form', ['item' => new Certificate]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = ImageUpload::store($request->file('image'));
        }

        Certificate::create($data);

        return redirect()->route('admin.certificates.index')->with('status', 'Sertifikat ditambahkan.');
    }

    public function edit(Certificate $certificate): View
    {
        return view('admin.certificates.form', ['item' => $certificate]);
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            ImageUpload::delete($certificate->image);
            $data['image'] = ImageUpload::store($request->file('image'));
        }

        $certificate->update($data);

        return redirect()->route('admin.certificates.index')->with('status', 'Sertifikat diperbarui.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        ImageUpload::delete($certificate->image);
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('status', 'Sertifikat dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'issuer' => ['required', 'string', 'max:150'],
            'issued_at' => ['nullable', 'date', 'before_or_equal:today'],
            'verification_url' => ['nullable', 'url:http,https', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['image']);

        return $data;
    }
}
