<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function index(): View
    {
        return view('admin.education.index', [
            'items' => Education::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.education.form', ['item' => new Education]);
    }

    public function store(Request $request): RedirectResponse
    {
        Education::create($this->validated($request));

        return redirect()->route('admin.education.index')->with('status', 'Pendidikan ditambahkan.');
    }

    public function edit(Education $education): View
    {
        return view('admin.education.form', ['item' => $education]);
    }

    public function update(Request $request, Education $education): RedirectResponse
    {
        $education->update($this->validated($request));

        return redirect()->route('admin.education.index')->with('status', 'Pendidikan diperbarui.');
    }

    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();

        return redirect()->route('admin.education.index')->with('status', 'Pendidikan dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'school' => ['required', 'string', 'max:150'],
            'major' => ['nullable', 'string', 'max:150'],
            'period' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
