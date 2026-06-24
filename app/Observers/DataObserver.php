<?php

namespace App\Observers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Model;

class DataObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->runETL();
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->runETL();
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->runETL();
    }

    /**
     * Run the ETL Pipeline.
     */
    protected function runETL(): void
    {
        Artisan::call('etl:run', [
            '--trigger' => 'crud',
            '--source'  => 'DataObserver',
        ]);
    }
}
