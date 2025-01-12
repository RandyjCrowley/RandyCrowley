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
        Schema::create('storage_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('box_name');
            $table->text('description')->nullable();
            $table->string('slug')->unique(); // For the URL
            $table->timestamps();
        });

        Schema::create('box_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_box_id')->constrained()->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('storage_boxes');
        Schema::dropIfExists('box_photos');
    }
};
