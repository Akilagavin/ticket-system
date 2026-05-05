<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'content'   => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $comment = Comment::create([
            'content'   => $request->content,
            'ticket_id' => $request->ticket_id,
            'user_id'   => Auth::check() ? Auth::id() : null,
        ]);

        // This line is what prevents the GET error on refresh
        return redirect()->back()->with('success', 'Your reply has been added successfully.');
    }
}