<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Auth::user()->tasks()
            ->orderBy('deadline', 'asc')
            ->orderBy('priority', 'desc');

        if ($request->has('priority')) {
            $query->where('priority', $request->query('priority'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->has('due_date')) {
            $query->whereDate('deadline', $request->query('due_date'));
        }

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        $projects = Auth::user()->projects()->with('users')->get();
        $ownedProjects = Auth::user()->ownedProjects()->with('users')->get();
        $allProjects = $projects->merge($ownedProjects);
        
        // Get all unique users from these projects to allow assignment
        $projectUsers = $allProjects->pluck('users')->flatten()->unique('id');
        
        return view('tasks.create', compact('allProjects', 'projectUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after:now',
            'priority' => 'required|in:low,medium,high',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = Auth::user()->tasks()->create($validated);

        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            $task->assignee->notify(new \App\Notifications\TaskAssigned($task));
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }


    public function show(Task $task): View
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        
        $originalAssignee = $task->assigned_to;
        $task->update($validated);
        
        if ($task->assigned_to && $task->assigned_to !== Auth::id() && $task->assigned_to !== $originalAssignee) {
             $task->assignee->notify(new \App\Notifications\TaskAssigned($task));
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }


    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}