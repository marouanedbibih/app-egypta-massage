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
        Schema::create('colabs', function (Blueprint $table) {
            $table->unsignedBigInteger('departement_id');
            $table->unsignedBigInteger('user_id');
            // Primary key
            $table->primary(['departement_id','user_id']);
            // Foreign key
            $table->foreign('departement_id')->references('id')->on('departements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colabs');
    }
};
