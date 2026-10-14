<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $projects = Project::published()->get();

        return view('home', [
            'projects' => $projects,
            'education' => Education::orderBy('sort_order')->orderBy('id')->get(),
            'certificates' => Certificate::orderBy('sort_order')->orderByDesc('issued_at')->get(),
            'technologies' => config('portfolio.technologies'),
        ]);
    }
}
