<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Material;
use App\Models\Assignment;

class DashboardController extends Controller
{
    /**
     * Mengambil data yang relevan untuk dashboard berdasarkan peran user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'guru') {
            // Cek apakah dia wali kelas
            $homeroomClass = $user->homeroomClass;
            if ($homeroomClass) {
                // Logika data untuk Wali Kelas
                $data['is_homeroom_teacher'] = true;
                $data['homeroom_class_name'] = $homeroomClass->name;
                $data['student_count'] = $homeroomClass->students()->count();
                $data['class_grades'] = []; // Placeholder
            }

            // === PERBAIKAN KEDUA DI SINI ===
            // Kita spesifikasikan 'classes.id' untuk menghindari ambiguitas saat JOIN
            $teachingClassIds = $user->teachingClasses()->pluck('classes.id');
            
            // Logika data untuk Guru Mata Pelajaran
            $data['latest_materials'] = Material::whereIn('class_id', $teachingClassIds)
                ->latest()->take(5)->get();
            $data['latest_assignments'] = Assignment::whereIn('class_id', $teachingClassIds)
                ->latest()->take(5)->get();
            $data['teaching_class_count'] = $user->teachingClasses()->count();

        } elseif ($user->role === 'murid') {
            // Logika data untuk Murid
            $studentClass = $user->studentClass()->first();
            if ($studentClass) {
                $submittedAssignmentIds = $user->submissions()->pluck('assignment_id');

                $data['latest_materials'] = Material::where('class_id', $studentClass->id)
                    ->latest()->take(5)->get();
                
                $data['pending_assignments'] = Assignment::where('class_id', $studentClass->id)
                    ->whereNotIn('assignments.id', $submittedAssignmentIds) 
                    ->latest()->get();
                
                $data['latest_grades'] = $user->submissions()->whereNotNull('grade')
                    ->latest()->take(5)->get();
            }
        }

        return response()->json($data);
    }
}

