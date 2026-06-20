<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'description', 'image', 'avatar', 'views', 'status', 'reviewed_at',
    ];
}
