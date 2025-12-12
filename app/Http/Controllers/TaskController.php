<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Auth::user()->tasks()
            ->orderBy('deadline', 'asc')
            ->orderBy('priority', 'desc')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after:now',
            'priority' => 'required|in:low,medium,high',
        ]);

        Auth::user()->tasks()->create($validated);

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
        ]);

        $task->update($validated);

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