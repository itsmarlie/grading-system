<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $announcements = Announcement::with('author')
            ->where(function ($q) use ($user) {
                $q->where('visibility', 'all')
                  ->orWhere('visibility', $user->role === 'student' ? 'students' : 'teachers');
            })
            ->latest()
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'category'   => 'required|in:General,Academic,Event',
            'visibility' => 'required|in:all,teachers,students',
        ]);

        Announcement::create([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'body'       => $request->body,
            'category'   => $request->category,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully!');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'category'   => 'required|in:General,Academic,Event',
            'visibility' => 'required|in:all,teachers,students',
        ]);

        $announcement->update($request->only('title', 'body', 'category', 'visibility'));

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated!');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted.');
    }
}