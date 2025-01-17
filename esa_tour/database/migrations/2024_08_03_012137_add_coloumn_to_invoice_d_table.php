<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_d', function (Blueprint $table) {
            $table->unsignedBigInteger('from_bank')->nullable()->change();
            $table->unsignedBigInteger('deposit_id')->nullable();

            $table->foreign('deposit_id')->references('id')->on('deposit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_d', function (Blueprint $table) {
            //
        });
    }
};
