<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Mengumpulkan tugas (hanya murid).
     */
    public function store(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        if ($user->role !== 'murid') {
            return response()->json(['message' => 'Hanya murid yang bisa mengumpulkan tugas'], 403);
        }

        // Cek apakah murid sudah pernah submit
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)->exists();

        if ($existingSubmission) {
            return response()->json(['message' => 'Anda sudah mengumpulkan tugas ini'], 422);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:10240', // Max 10MB
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        $submission = Submission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'file_path' => $filePath,
        ]);

        return response()->json($submission, 201);
    }

    /**
     * Update submission untuk memberi nilai (hanya guru).
     */
    public function update(Request $request, Submission $submission)
    {
        $user = Auth::user();
        // Otorisasi: Cek apakah user adalah guru dari tugas ini
        $isTeacherOfThisAssignment = $submission->assignment->user_id === $user->id;

        if ($user->role !== 'guru' || !$isTeacherOfThisAssignment) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update($validated);

        return response()->json($submission);
    }
}
