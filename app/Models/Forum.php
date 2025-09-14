<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id', 'class_id', 'subject_id'];

    /**
     * Topik forum ini dibuat oleh siapa (User).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Forum ini milik kelas mana.
     * Nama fungsi diubah dari 'class' (kata kunci terlarang) menjadi 'kelas'.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    /**
     * Forum ini untuk mata pelajaran apa.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Topik forum ini memiliki banyak komentar.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

