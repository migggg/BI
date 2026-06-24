<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE dim_offices MODIFY employee_key BIGINT UNSIGNED NULL, MODIFY employee_name VARCHAR(255) NULL, MODIFY job_title VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dim_offices', function (Blueprint $table) {
            //
        });
    }
};
