<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Change 'role' column to an integer with default value 0
            $table->integer('role')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // If needed, you can define the reverse operation here
            // For example, changing the column back to enum
            // $table->enum('role', [0, 1, 2])->default(0)->change();
        });
    }
};
