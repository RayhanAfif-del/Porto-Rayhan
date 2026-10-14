<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\ImageUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::orderBy('sort_order')->orderBy('id')->get();
        $totalCount = $projects->count();
        $publishedCount = $projects->where('published', true)->count();
        $categoryCounts = $projects->groupBy('category')->map->count();

        return view('admin.projects.index', [
            'projects' => $projects,
            'totalCount' => $totalCount,
            'publishedCount' => $publishedCount,
            'categoryCounts' => $categoryCounts,
            'categoryCount' => $categoryCounts->count(),
        ]);
    }

    public function togglePublish(Project $project): RedirectResponse
    {
        $project->update(['published' => ! $project->published]);

        $statusText = $project->published ? 'dipublikasikan' : 'diarsipkan (draft)';

        return back()->with('status', "Proyek \"{$project->title}\" sekarang {$statusText}.");
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['published' => true, 'category' => 'web', 'sort_order' => 0]),
            'categories' => Project::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = ImageUpload::store($request->file('image'));
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('status', 'Proyek ditambahkan.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'categories' => Project::CATEGORIES,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            ImageUpload::delete($project->image);
            $data['image'] = ImageUpload::store($request->file('image'));
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', 'Proyek diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        ImageUpload::delete($project->image);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Proyek dihapus.');
    }

    private function validated(Request $request): array
    {
        if ($request->filled('demo_url') && ! str_starts_with((string) $request->input('demo_url'), 'http')) {
            $request->merge(['demo_url' => 'https://'.ltrim((string) $request->input('demo_url'), '/')]);
        }
        if ($request->filled('repository_url') && ! str_starts_with((string) $request->input('repository_url'), 'http')) {
            $request->merge(['repository_url' => 'https://'.ltrim((string) $request->input('repository_url'), '/')]);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:1000'],
            'category' => ['required', Rule::in(array_keys(Project::CATEGORIES))],
            'technologies' => ['nullable', 'string', 'max:500'],
            'demo_url' => ['nullable', 'url:http,https', 'max:255'],
            'repository_url' => ['nullable', 'url:http,https', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['technologies'] = collect(explode(',', (string) ($data['technologies'] ?? '')))
            ->map(fn ($t) => trim($t))->filter()->values()->all();
        $data['published'] = $request->boolean('published');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['image']);

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'proyek';
        $slug = $base;
        $i = 2;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
