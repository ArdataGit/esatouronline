<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_d', function (Blueprint $table) {
            $table->string('status_debt')->nullable();
            $table->date('date_payment_debt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invoice_d', function (Blueprint $table) {
            //
        });
    }
};
