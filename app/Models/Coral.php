<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coral extends Model
{
    use HasFactory;

    protected $table = 'corals';

    protected $fillable = [
        'condition',
        'radius',
        'long',
        'lat',
    ];

}
