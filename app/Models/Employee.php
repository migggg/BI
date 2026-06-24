<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use \App\Traits\TriggersETL;

    protected $table = 'employees';
    protected $primaryKey = 'employeeNumber';
    public $timestamps = false;

    protected $fillable = [
        'employeeNumber',
        'lastName',
        'firstName',
        'extension',
        'email',
        'officeCode',
        'reportsTo',
        'jobTitle',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'officeCode', 'officeCode');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'reportsTo', 'employeeNumber');
    }
}
