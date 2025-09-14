<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'file_path',
        'grade',
        'feedback',
        'user_id', // ID murid yang mengumpulkan
    ];

    /**
     * Jawaban ini untuk tugas yang mana.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Jawaban ini milik siapa (murid).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
