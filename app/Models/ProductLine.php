<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    protected $table = 'productlines';
    protected $primaryKey = 'productLine';
    
    public $timestamps = false;

    protected $fillable = [
        'productLine',
        'textDescription',
        'htmlDescription',
        'image',
    ];
}
