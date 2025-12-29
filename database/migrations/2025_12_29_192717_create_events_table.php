<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Client-side identifier (UUID) for deduplication / offline sync
            $table->uuid('client_id')->nullable()->index();

            // Basic descriptors
            $table->string('op_number')->nullable()->index();
            $table->string('title')->nullable();

            // Date/time fields
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->boolean('all_day')->default(false);

            // Status and payload
            $table->string('estado')->default('pendiente')->index();
            $table->json('codigos_json')->nullable();

            // Quantities and delivery/production dates
            $table->integer('cantidad_req')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_produccion')->nullable();

            // Metadata for sync/conflict resolution
            $table->string('created_by')->nullable();
            $table->unsignedInteger('version')->default(1);

            // Indexes for queries
            $table->index(['start']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
