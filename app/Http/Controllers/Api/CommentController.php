<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru pada sebuah forum.
     */
    public function store(Request $request, Forum $forum)
    {
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $forum->comments()->create([
            'body' => $validated['body'],
            'user_id' => Auth::id(),
        ]);

        // Load relasi 'creator' agar data user pembuat komentar ikut terkirim
        return response()->json($comment->load('creator'), 201);
    }
}

