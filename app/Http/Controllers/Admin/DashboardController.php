<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $projectsCount = Project::count();
        $publishedProjectsCount = Project::where('published', true)->count();
        $educationCount = Education::count();
        $certificatesCount = Certificate::count();
        $unreadMessagesCount = ContactMessage::whereNull('read_at')->count();

        return view('admin.dashboard', [
            'projectsCount' => $projectsCount,
            'publishedProjectsCount' => $publishedProjectsCount,
            'educationCount' => $educationCount,
            'certificatesCount' => $certificatesCount,
            'unreadMessagesCount' => $unreadMessagesCount,
            'latestProjects' => Project::orderBy('sort_order')->orderBy('id')->limit(4)->get(),
            'primaryEducation' => Education::orderBy('sort_order')->orderBy('id')->first(),
            'latestMessages' => ContactMessage::latest()->limit(5)->get(),
        ]);
    }
}
