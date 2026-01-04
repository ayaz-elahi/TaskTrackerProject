<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class InvitationController extends Controller
{
    public function accept(string $token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('dashboard')->with('error', 'Invalid invitation token.');
        }

        $project = $invitation->project;

        // Check if user is already a member
        if (!$project->users()->where('user_id', Auth::id())->exists()) {
            $project->users()->attach(Auth::id(), ['role' => 'member']);
        }

        $invitation->delete();

        return redirect()->route('projects.show', $project)->with('success', 'You have joined the project!');
    }
}
