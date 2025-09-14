<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Menampilkan daftar tugas dengan fungsionalitas pencarian.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        $user = Auth::user();
        $classIds = [];

        if ($user->role === 'guru') {
            $classIds = $user->teachingClasses()->pluck('classes.id');
        } elseif ($user->role === 'murid') {
            $classIds = $user->studentClass()->pluck('classes.id');
        }

        // Mulai membangun query dasar
        $query = Assignment::with(['class', 'subject', 'submissions' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->whereIn('class_id', $classIds);

        // Tambahkan logika pencarian jika ada parameter 'search'
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $assignments = $query->latest()->get();

        return response()->json($assignments);
    }

    /**
     * Menyimpan tugas baru (hanya guru).
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'guru') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'nullable|date',
        ]);

        $assignment = Assignment::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        return response()->json($assignment, 201);
    }

    /**
     * Menampilkan detail satu tugas.
     */
    public function show(Assignment $assignment)
    {
        // Eager load submissions beserta data muridnya
        return $assignment->load('class', 'subject', 'creator', 'submissions.student');
    }
}

