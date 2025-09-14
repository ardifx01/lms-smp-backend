<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit karena nama model 'Kelas' (singular) 
    // akan otomatis mencari tabel 'kelas' (plural 's' tidak tepat untuk Bahasa Indonesia).
    // Konvensi Laravel akan mencari 'kelases'.
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'homeroom_teacher_id',
    ];

    /**
     * Mendapatkan data Wali Kelas (user) dari kelas ini.
     */
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    /**
     * Mendapatkan semua murid (users) yang ada di kelas ini.
     * Menggunakan tabel pivot 'class_user'.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id');
    }

    /**
     * Mendapatkan semua mata pelajaran yang diajarkan di kelas ini.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject_teacher', 'class_id', 'subject_id');
    }

    /**
     * Mendapatkan semua materi yang ada di kelas ini.
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id');
    }

    /**
     * Mendapatkan semua tugas yang ada di kelas ini.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    /**
     * Mendapatkan semua forum yang ada di kelas ini.
     */
    public function forums()
    {
        return $this->hasMany(Forum::class, 'class_id');
    }
}
