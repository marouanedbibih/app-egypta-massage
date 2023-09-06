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
        Schema::create('durations', function (Blueprint $table) {
            $table->unsignedBigInteger('type_service_id');
            $table->integer('duration');
            $table->float('price');
            // Foreign key
            $table->foreign('type_service_id')->references('id')->on('type_services')->onDelete('cascade');
            // Primary key
            $table->primary(['duration','type_service_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duration');
    }
};
