<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Menghasilkan dan mengunduh laporan nilai dalam format PDF untuk satu kelas.
     */
    public function exportGradesPdf(Kelas $kelas)
    {
        // Ambil semua ID siswa dari kelas yang diberikan
        $studentIds = $kelas->students()->pluck('users.id');

        // Ambil semua data submission (yang sudah dinilai) dari siswa-siswa tersebut
        $submissions = Submission::with('assignment.subject', 'student')
            ->whereIn('user_id', $studentIds)
            ->whereNotNull('grade')
            ->get();
            
        // Kelompokkan hasil submission berdasarkan nama siswa
        $gradesByStudent = $submissions->groupBy('student.name');

        // Siapkan data untuk dikirim ke view
        $data = [
            'kelas' => $kelas,
            'gradesByStudent' => $gradesByStudent,
        ];
        
        // Muat view Blade dengan data, lalu konversi ke PDF
        $pdf = Pdf::loadView('exports.grades', $data);

        // Buat nama file yang dinamis dan mulai unduh
        $fileName = 'laporan-nilai-' . $kelas->name . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}

