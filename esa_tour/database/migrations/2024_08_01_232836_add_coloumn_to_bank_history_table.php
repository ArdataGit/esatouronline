<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            $table->unsignedBigInteger('deposit_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();

            $table->foreign('deposit_id')->references('id')->on('deposit');
            $table->foreign('asset_id')->references('id')->on('asset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            //
        });
    }
};
