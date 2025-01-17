<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('name');
            $table->string('description')->nullable();

            $table->foreign('city_id')->references('id')->on('cities');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};
