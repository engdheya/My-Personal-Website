<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    /**
     * GET /api/projects
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::query()->orderByDesc('is_featured')->latest();

        if ($request->filled('tech')) {
            $query->where('technologies', 'like', '%'.$request->string('tech').'%');
        }

        $projects = $query->paginate(min((int) $request->integer('per_page', 12), 50));

        return response()->json([
            'data' => $projects->getCollection()->map(fn (Project $project) => $this->transform($project)),
            'meta' => [
                'current_page' => $projects->currentPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'last_page' => $projects->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/projects/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        return response()->json(['data' => $this->transform($project, true)]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function transform(Project $project, bool $withDetails = false): array
    {
        $data = [
            'id' => $project->id,
            'title' => $project->title,
            'title_ar' => $project->title_ar,
            'slug' => $project->slug,
            'short_description' => $project->short_description,
            'short_description_ar' => $project->short_description_ar,
            'main_image' => $project->main_image,
            'technologies' => array_values(array_filter(array_map('trim', explode(',', (string) $project->technologies)))),
            'category' => $project->category,
            'status' => $project->status,
            'project_date' => $project->project_date,
            'is_featured' => (bool) $project->is_featured,
            'github_url' => $project->github_url,
            'live_url' => $project->live_url,
            'url' => url('/projects/'.$project->slug),
        ];

        if ($withDetails) {
            $data['description'] = $project->description;
            $data['description_ar'] = $project->description_ar;
            $data['problem'] = $project->problem;
            $data['solution'] = $project->solution;
            $data['gallery'] = $project->gallery ?? [];
            $data['features'] = $project->features ?? [];
        }

        return $data;
    }
}
