<?php
namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Submission;

    class GradeController extends Controller
    {
        public function index()
        {
            $user = Auth::user();
            $submissions = collect(); // Buat koleksi kosong

            if ($user->role === 'murid') {
                $submissions = $user->submissions()->with('assignment.subject')
                    ->whereNotNull('grade')
                    ->get();
            } else if ($user->role === 'guru') {
                // Jika guru adalah wali kelas, ambil semua nilai murid di kelasnya
                if ($homeroomClass = $user->homeroomClass) {
                    $studentIds = $homeroomClass->students()->pluck('users.id');
                    $submissions = Submission::with('assignment.subject', 'student')
                        ->whereIn('user_id', $studentIds)
                        ->whereNotNull('grade')
                        ->get();
                }
                // Logika untuk guru mapel biasa bisa ditambahkan di sini jika perlu
            }

            // Kelompokkan nilai berdasarkan mata pelajaran
            $gradesBySubject = $submissions->groupBy('assignment.subject.name');

            return response()->json($gradesBySubject);
        }
    }
    
