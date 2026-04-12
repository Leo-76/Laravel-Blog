<?php

namespace CafeHistoire\CafeBlog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Comment;

class CommentAdminController extends Controller
{
    public function index(Request $request)
    {
        $comments = Comment::with('post')
            ->when($request->input('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(25);

        return view('cafe-blog::admin.comments.index', compact('comments'));
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,spam,trash'],
        ]);

        $comment->update($validated);

        return back()->with('success', 'Commentaire mis à jour.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }
}
