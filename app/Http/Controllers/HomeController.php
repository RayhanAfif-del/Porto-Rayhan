<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $projects = Project::published()->get();

        $techs = setting('technologies.list', config('portfolio.technologies', []));
        if (is_string($techs)) {
            $techs = json_decode($techs, true) ?? config('portfolio.technologies', []);
        }

        return view('home', [
            'projects' => $projects,
            'education' => Education::orderBy('sort_order')->orderBy('id')->get(),
            'experiences' => Experience::orderBy('sort_order')->orderBy('id')->get(),
            'technologies' => is_array($techs) ? $techs : [],
        ]);
    }
}
