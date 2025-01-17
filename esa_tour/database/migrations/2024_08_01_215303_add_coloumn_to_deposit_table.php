<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('deposit', function (Blueprint $table) {
            $table->float('beginning_balance')->default(0);
            $table->float('income')->default(0);
            $table->float('expense')->default(0);
            $table->renameColumn('nominal', 'balance');
            // $table->float('balance')->change();
        });
    }

    public function down(): void
    {
        Schema::table('deposit', function (Blueprint $table) {
            //
        });
    }
};
