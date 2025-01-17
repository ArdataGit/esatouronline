<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_category');
            $table->float('sale')->nullable()->default(0);
            $table->float('purchase')->nullable()->default(0);
            $table->float('profit')->nullable()->default(0);
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();


            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
