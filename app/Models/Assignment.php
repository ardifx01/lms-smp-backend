<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'class_id',
        'subject_id',
        'user_id', // ID guru yang membuat tugas
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Tugas ini diberikan untuk kelas mana.
     */
    public function class()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    /**
     * Tugas ini untuk mata pelajaran apa.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Tugas ini dibuat oleh siapa (guru).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan semua jawaban (submissions) untuk tugas ini.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
