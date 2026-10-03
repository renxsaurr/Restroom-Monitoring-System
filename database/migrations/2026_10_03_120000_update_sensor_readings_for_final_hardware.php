<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            // Retain the old columns as nullable so existing records are preserved.
            $table->unsignedInteger('visitors')->nullable()->change();
            $table->decimal('water_level', 10, 2)->nullable()->change();
            $table->decimal('air_quality', 10, 2)->nullable()->change();

            $table->unsignedInteger('people_count')->nullable();
            $table->unsignedSmallInteger('tcs_red')->nullable();
            $table->unsignedSmallInteger('tcs_green')->nullable();
            $table->unsignedSmallInteger('tcs_blue')->nullable();
            $table->unsignedSmallInteger('tcs_clear')->nullable();
            $table->string('water_state', 32)->nullable();
            $table->unsignedInteger('mq135_raw')->nullable();
            $table->string('mq135_state', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            $table->dropColumn([
                'people_count',
                'tcs_red',
                'tcs_green',
                'tcs_blue',
                'tcs_clear',
                'water_state',
                'mq135_raw',
                'mq135_state',
            ]);
        });
    }
};
