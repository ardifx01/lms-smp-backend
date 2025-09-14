<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Mendapatkan semua kelas di mana mata pelajaran ini diajarkan.
     */
    public function classes()
    {
        return $this->belongsToMany(Kelas::class, 'class_subject_teacher', 'subject_id', 'class_id');
    }

    /**
     * Mendapatkan semua guru yang mengajar mata pelajaran ini.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_subject_teacher', 'subject_id', 'teacher_id');
    }
}
