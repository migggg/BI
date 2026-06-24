<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;

trait TriggersETL
{
    /**
     * Boot the trait for a model.
     *
     * @return void
     */
    public static function bootTriggersETL()
    {
        static::saved(function ($model) {
            Artisan::call('etl:run', [
                '--trigger' => 'crud',
                '--source' => class_basename($model) . ' saved'
            ]);
        });

        static::deleted(function ($model) {
            Artisan::call('etl:run', [
                '--trigger' => 'crud',
                '--source' => class_basename($model) . ' deleted'
            ]);
        });
    }
}
