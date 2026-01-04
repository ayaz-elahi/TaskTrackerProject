<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Auth::user()->projects()->get();
        $ownedProjects = Auth::user()->ownedProjects()->get();
        // Merge or display separately
        $allProjects = $projects->merge($ownedProjects);
        
        return view('projects.index', compact('allProjects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Auth::user()->ownedProjects()->create($validated);
        // Add owner as a member too with role owner
        $project->users()->attach(Auth::id(), ['role' => 'owner']);

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully!');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);
        $project->load('users', 'tasks');
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);
        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }

    public function invite(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project); // Only owners/admins can invite
        
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Check if user is already a member
        if ($project->users()->where('email', $validated['email'])->exists()) {
            return back()->withErrors(['email' => 'User is already a member.']);
        }

        // Logic to send invitation (simplification: just create an Invitation record)
        Invitation::create([
            'project_id' => $project->id,
            'email' => $validated['email'],
            'token' => Str::random(32),
        ]);

        // In a real app, send mail here.
        
        return back()->with('success', 'Invitation sent!');
    }

    public function removeMember(Project $project, User $user): RedirectResponse
    {
        $this->authorize('update', $project);

        if ($user->id === $project->owner_id) {
            return back()->with('error', 'Cannot remove the project owner.');
        }

        $project->users()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }

    public function leave(Project $project): RedirectResponse
    {
        // Check if user is the owner
        if (Auth::id() === $project->owner_id) {
            return back()->with('error', 'Owner cannot leave the project. Delete it instead or transfer ownership.');
        }

        if (!$project->users()->where('user_id', Auth::id())->exists()) {
             return back()->with('error', 'You are not a member of this project.');
        }

        $project->users()->detach(Auth::id());

        return redirect()->route('projects.index')->with('success', 'You have left the project.');
    }
}
