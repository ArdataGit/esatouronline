<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposit', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable()->change();
            $table->longText('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('deposit', function (Blueprint $table) {
            //
        });
    }
};
