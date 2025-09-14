<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELASI UNTUK PERAN GURU ---

    /**
     * Mendapatkan kelas di mana user ini menjadi Wali Kelas.
     */
    public function homeroomClass()
    {
        return $this->hasOne(Kelas::class, 'homeroom_teacher_id');
    }

    /**
     * Mendapatkan daftar kelas dan mapel yang diajar oleh guru ini.
     * Menggunakan tabel pivot 'class_subject_teacher'.
     */
    public function teachingClasses()
    {
        return $this->belongsToMany(Kelas::class, 'class_subject_teacher', 'teacher_id', 'class_id')->withTimestamps();
    }
    
    public function teachingSubjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject_teacher', 'teacher_id', 'subject_id')->withTimestamps();
    }


    // --- RELASI UNTUK PERAN MURID ---

    /**
     * Mendapatkan kelas di mana user ini terdaftar sebagai murid.
     * Menggunakan tabel pivot 'class_user'.
     */
    public function studentClass()
    {
        // Asumsi satu murid hanya di satu kelas
        return $this->belongsToMany(Kelas::class, 'class_user', 'user_id', 'class_id')->withTimestamps();
    }

    /**
     * Mendapatkan semua pengumpulan tugas (submission) yang dibuat oleh murid ini.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }


    // --- RELASI UMUM ---

    /**
     * Materi yang diupload oleh user (guru).
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Tugas yang dibuat oleh user (guru).
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Forum yang dibuat oleh user (guru atau murid).
     */
    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    /**
     * Komentar yang dibuat oleh user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
