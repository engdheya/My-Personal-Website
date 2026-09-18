<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $tech = $request->query('tech');
        $selectedFilter = is_string($tech) && $tech !== '' ? $tech : null;

        $query = Project::query();

        if ($selectedFilter) {
            $query->where('technologies', 'like', '%'.$selectedFilter.'%');
        }

        $projects = $query
            ->orderByDesc('is_featured')
            ->latest('id')
            ->get();

        $breadcrumbs = [
            ['name' => __('site.nav_projects'), 'url' => '/projects'],
        ];

        return view('projects.index', [
            'projects' => $projects,
            'selectedFilter' => $selectedFilter,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        $relatedProjects = Project::whereKeyNot($project->getKey())
            ->latest('id')
            ->take(2)
            ->get();

        $breadcrumbs = [
            ['name' => __('site.nav_projects'), 'url' => '/projects'],
            ['name' => loc_field($project, 'title'), 'url' => '/projects/'.$project->slug],
        ];

        return view('projects.show', [
            'project' => $project,
            'relatedProjects' => $relatedProjects,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
