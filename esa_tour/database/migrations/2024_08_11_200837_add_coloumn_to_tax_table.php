<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tax', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable();

            $table->foreign('bank_id')->references('id')->on('bank');
        });
    }

 
    public function down(): void
    {
        Schema::table('tax', function (Blueprint $table) {
            //
        });
    }
};
