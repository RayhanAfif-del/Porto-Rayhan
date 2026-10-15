<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences.index', [
            'items' => Experience::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.experiences.form', ['item' => new Experience]);
    }

    public function store(Request $request): RedirectResponse
    {
        Experience::create($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Pengalaman berhasil ditambahkan.');
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.form', ['item' => $experience]);
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Pengalaman berhasil diperbarui.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('status', 'Pengalaman berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'max:150'],
            'company' => ['required', 'string', 'max:150'],
            'period' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'skills' => ['nullable'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if (isset($data['skills'])) {
            if (is_string($data['skills'])) {
                $data['skills'] = array_values(array_filter(array_map('trim', explode(',', $data['skills']))));
            } elseif (! is_array($data['skills'])) {
                $data['skills'] = [];
            }
        } else {
            $data['skills'] = [];
        }

        return $data;
    }
}
