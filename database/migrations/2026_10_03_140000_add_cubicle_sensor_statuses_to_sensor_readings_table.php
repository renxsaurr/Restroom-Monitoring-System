<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            $table->boolean('cubicle_1_occupied')->nullable();
            $table->boolean('cubicle_2_occupied')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            $table->dropColumn(['cubicle_1_occupied', 'cubicle_2_occupied']);
        });
    }
};
