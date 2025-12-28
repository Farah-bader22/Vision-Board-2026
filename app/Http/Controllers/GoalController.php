<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class GoalController extends Controller
{

    public function index()
    {
        return Inertia::render('Dashboard', [
            'goals' => auth()->user()->goals()
                ->where('is_archived', false)
                ->latest()
                ->get()
        ]);
    }


    public function archiveIndex()
    {
        return Inertia::render('Archive', [
            'archivedGoals' => auth()->user()->goals()
                ->where('is_archived', true)
                ->latest()
                ->get()
        ]);
    }

    public function stats()
    {
        $goals = auth()->user()->goals;

        $stats = $goals->groupBy('category')->map(fn($group) => $group->count());

        return Inertia::render('Stats', [
            'categories' => $stats->keys(),
            'counts' => $stats->values(),
            'totalGoals' => $goals->count(),
            'avgProgress' => round($goals->avg('progress') ?? 0),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'progress' => 'required|integer|min:0|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('goals', 'public');
        }

        $request->user()->goals()->create($validated);
        return back();
    }

    public function update(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'progress' => 'required|integer|min:0|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($goal->image_path) Storage::disk('public')->delete($goal->image_path);
            $validated['image_path'] = $request->file('image')->store('goals', 'public');
        }

        $goal->update($validated);
        return back();
    }


    public function toggleArchive(Goal $goal)
    {
        
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        $goal->update([
            'is_archived' => !$goal->is_archived
        ]);

        return back();
    }

    public function destroy(Goal $goal)
    {
        if ($goal->image_path) Storage::disk('public')->delete($goal->image_path);
        $goal->delete();
        return back();
    }
}
