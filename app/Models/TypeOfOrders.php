<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoryId',
        'searchId',
        'name',
        'nameRu',
        'techDocumentations',
    ];


}
