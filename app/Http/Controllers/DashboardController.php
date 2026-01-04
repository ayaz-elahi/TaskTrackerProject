<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Tasks assigned to me or created by me (depending on requirement, usually assigned)
        $tasksQuery = $user->tasks(); 
        
        $totalTasks = $tasksQuery->count();
        $completedTasks = $tasksQuery->where('status', 'completed')->count();
        $pendingTasks = $tasksQuery->where('status', 'pending')->count();
        $inProgressTasks = $tasksQuery->where('status', 'in_progress')->count();

        $projectCount = $user->projects()->count() + $user->ownedProjects()->count();

        return view('dashboard', compact('totalTasks', 'completedTasks', 'pendingTasks', 'inProgressTasks', 'projectCount'));
    }
}
