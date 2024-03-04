<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRows extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'categories',
        'categoryId',

    ];
}

