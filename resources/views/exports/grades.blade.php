<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1, h2 { text-align: center; }
    </style>
</head>
<body>

    <h1>Laporan Rekap Nilai</h1>
    <h2>Kelas: {{ $kelas->name }}</h2>
    <p>Wali Kelas: {{ $kelas->homeroomTeacher->name }}</p>
    <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    <hr>

    @foreach($gradesByStudent as $studentName => $submissions)
        <div style="margin-top: 20px;">
            <h3><strong>Siswa: {{ $studentName }}</strong></h3>
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Judul Tugas</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr>
                            <td>{{ $submission->assignment->subject->name ?? 'N/A' }}</td>
                            <td>{{ $submission->assignment->title ?? 'N/A' }}</td>
                            <td>{{ $submission->grade }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>