<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credit_debit', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('category_note_id')->nullable();

            $table->foreign('invoice_id')->references('id')->on('invoice');
            $table->foreign('category_note_id')->references('id')->on('category_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_debit', function (Blueprint $table) {
            //
        });
    }
};
