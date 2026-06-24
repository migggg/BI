<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Upgrade the etl_logs table with proper audit columns for the ETL pipeline.
     */
    public function up(): void
    {
        Schema::dropIfExists('etl_logs');

        Schema::create('etl_logs', function (Blueprint $table) {
            $table->id();
            $table->string('run_id', 36)->unique()->comment('Unique UUID for each ETL run');
            $table->enum('trigger_type', ['manual', 'scheduled', 'crud', 'seed'])->default('manual');
            $table->string('trigger_source')->nullable()->comment('Which CRUD action or schedule triggered this run');
            $table->enum('status', ['running', 'completed', 'failed'])->default('running');
            $table->unsignedInteger('dim_customers_count')->default(0);
            $table->unsignedInteger('dim_products_count')->default(0);
            $table->unsignedInteger('dim_offices_count')->default(0);
            $table->unsignedInteger('fact_sales_count')->default(0);
            $table->unsignedInteger('total_rows_processed')->default(0);
            $table->decimal('duration_seconds', 8, 3)->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('trigger_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etl_logs');

        Schema::create('etl_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
