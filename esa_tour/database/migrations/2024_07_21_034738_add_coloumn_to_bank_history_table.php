<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')->references('id')->on('products');
        });
    }

 
    public function down(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            
        });
    }
};
