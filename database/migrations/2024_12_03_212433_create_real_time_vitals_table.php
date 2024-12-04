<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('real_time_vitals', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->integer('dsn');
            $table->integer('charge_status');
            $table->integer('heart_rate');
            $table->integer('movement');
            $table->integer('sensor_code');
            $table->integer('status_code');
            $table->integer('base_station_on');
            $table->integer('battery_percentage');
            $table->integer('battery_temperature');
            $table->integer('charging_status');
            $table->integer('alert_status');
            $table->integer('ota_status');
            $table->integer('sensor_fault');
            $table->integer('rssi');
            $table->integer('sensor_base_status');
            $table->integer('sensor_status');
            $table->integer('movement_vibration');
            $table->timestamp('timestamp');
            $table->integer('oxygen_saturation');
            $table->integer('operation_mode');
            $table->integer('base_station_status');
            $table->integer('mode_status');
            $table->string('hardware_version');
            $table->string('error');
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('real_time_vitals');
    }
};
