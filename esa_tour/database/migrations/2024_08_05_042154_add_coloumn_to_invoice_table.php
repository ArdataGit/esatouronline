<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->string('status_receivables')->default('Belum Lunas');
            $table->string('status_debt')->default('Belum Lunas');
            $table->string('status')->default('Aktif')->change();
        });
    }


    public function down(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            //
        });
    }
};
