<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use \App\Traits\TriggersETL;

    protected $table = 'payments';
    protected $primaryKey = 'checkNumber';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'customerNumber',
        'checkNumber',
        'paymentDate',
        'amount',
    ];
}
