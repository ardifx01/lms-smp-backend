<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'class_id',
        'subject_id',
        'user_id',
    ];

    /**
     * Mendapatkan data kelas tempat materi ini berada.
     */
    public function class(): BelongsTo
    {
        // Relasi ke model Kelas, melalui foreign key 'class_id'
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    /**
     * Mendapatkan data mata pelajaran dari materi ini.
     */
    public function subject(): BelongsTo
    {
        // Relasi ke model Subject, melalui foreign key 'subject_id'
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Mendapatkan data user (guru) yang mengupload materi ini.
     */
    public function uploader(): BelongsTo
    {
        // Relasi ke model User, melalui foreign key 'user_id'
        return $this->belongsTo(User::class, 'user_id');
    }
}

