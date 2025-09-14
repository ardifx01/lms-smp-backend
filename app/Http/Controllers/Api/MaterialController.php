<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Menampilkan daftar materi dengan fungsionalitas pencarian.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        $user = Auth::user();
        $classIds = []; // Inisialisasi array untuk ID kelas

        if ($user->role === 'guru') {
            $classIds = $user->teachingClasses()->pluck('classes.id');
        } elseif ($user->role === 'murid') {
            $classIds = $user->studentClass()->pluck('classes.id');
        }

        // Mulai membangun query
        $query = Material::with('class', 'subject')->whereIn('class_id', $classIds);

        // Tambahkan logika pencarian jika ada parameter 'search'
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $materials = $query->latest()->get();

        return response()->json($materials);
    }

    /**
     * Menyimpan materi baru (hanya guru).
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'guru') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,jpg,png|max:10240', // Max 10MB
        ]);
        
        $filePath = $request->file('file')->store('materials', 'public');

        $material = Material::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($material, 201);
    }

    /**
     * Menampilkan detail satu materi.
     */
    public function show(Material $material)
    {
        // TODO: Tambahkan otorisasi untuk memastikan user (murid/guru) ada di kelas yang sama
        return response()->json($material->load('class', 'subject', 'uploader'));
    }

    /**
     * Menghapus materi (hanya guru yang membuat).
     */
    public function destroy(Material $material)
    {
        if (Auth::id() !== $material->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($material->file_path);
        
        $material->delete();

        return response()->json(null, 204); // No Content
    }
}

