<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects for the ACTIVE company.
     */
    public function index()
    {
        // Because of the Global Scope Trait, this ONLY returns 
        // projects where company_id = current_company_id
        return response()->json([
            'projects' => Project::latest()->get()
        ]);
    }


    /**
     * Store a new project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'company_id' => Auth::user()->current_company_id,
        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project
        ], 211);
    }

    public function destroy(Project $project)
    {
        // 1. SECURITY: The Global Scope already ensures $project 
        // belongs to the current company. If it doesn't, Laravel returns 404.

        if (!auth()->user()->hasRole('owner')) {
            return response()->json(['message' => 'Only owners can delete projects.'], 403);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully.']);
    }
}
