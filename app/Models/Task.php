<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    // Relasi Task dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
