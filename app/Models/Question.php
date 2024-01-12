<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'choices',
        'ans'
    ];

    protected $casts = [
        'choices' => 'array',
        'ans' => 'array',
    ];
}
