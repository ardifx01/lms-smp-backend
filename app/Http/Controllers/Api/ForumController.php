<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Menampilkan daftar semua topik forum yang bisa diakses user.
     */
    public function index()
    {
        $user = Auth::user();
        $classIds = [];

        if ($user->role === 'murid') {
            $classIds = $user->studentClass()->pluck('classes.id');
        } else if ($user->role === 'guru') {
            $classIds = $user->teachingClasses()->pluck('classes.id');
        }

        $forums = Forum::with('creator', 'comments')
            ->whereIn('class_id', $classIds)
            ->latest()
            ->get();

        return response()->json($forums);
    }

    /**
     * Menyimpan topik forum baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $classId = $user->studentClass()->first()->id ?? $user->teachingClasses()->first()->id;

        if (!$classId) {
            return response()->json(['message' => 'Anda tidak terdaftar di kelas manapun.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $forum = Forum::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => $user->id,
            'class_id' => $classId,
        ]);

        return response()->json($forum->load('creator'), 201);
    }

    /**
     * Menampilkan detail satu topik forum beserta semua komentarnya.
     */
    public function show(Forum $forum)
    {
        // --- PENAMBAHAN LOGIKA OTORISASI ---
        $user = Auth::user();
        $userClassIds = [];

        if ($user->role === 'murid') {
            // Ambil ID kelas murid dan ubah menjadi array
            $userClassIds = $user->studentClass()->pluck('classes.id')->toArray();
        } else if ($user->role === 'guru') {
            // Ambil semua ID kelas yang diajar guru dan ubah menjadi array
            $userClassIds = $user->teachingClasses()->pluck('classes.id')->toArray();
        }

        // Jika ID kelas dari forum ini TIDAK ADA di dalam daftar kelas milik user, tolak akses.
        if (!in_array($forum->class_id, $userClassIds)) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke forum ini.'], 403);
        }
        
        // Jika otorisasi berhasil, tampilkan data forum beserta relasinya.
        return $forum->load('creator', 'comments.creator');
    }
}

