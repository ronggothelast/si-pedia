<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'nidn', 'nip', 'username', 'email', 'phone',
        'address', 'place_of_birth', 'date_of_birth', 'gender',
        'study_program', 'expertise', 'photo', 'status',
    ];
}
