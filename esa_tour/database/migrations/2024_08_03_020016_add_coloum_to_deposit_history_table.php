<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposit_history', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
            $table->unsignedBigInteger('deposit_id');

            $table->foreign('deposit_id')->references('id')->on('deposit');
        });
    }


    public function down(): void
    {
        Schema::table('deposit_history', function (Blueprint $table) {
            //
        });
    }
};
