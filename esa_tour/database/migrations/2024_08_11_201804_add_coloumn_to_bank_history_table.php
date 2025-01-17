<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_id')->nullable();

            $table->foreign('tax_id')->references('id')->on('tax');
        });
    }

    public function down(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            
        });
    }
};
