<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('tech')) {
            $query->where('technologies', 'like', "%{$request->tech}%");
        }

        $projects = $query->orderBy('is_featured', 'desc')->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $relatedProjects = Project::where('id', '!=', $project->id)->take(2)->get();

        return view('projects.show', compact('project', 'relatedProjects'));
    }
}
