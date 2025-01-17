<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_d', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('category_id');
            $table->string('product_name');
            $table->float('qty');
            $table->float('selling_price');
            $table->unsignedBigInteger('from_bank');
            $table->float('purchase_price');
            $table->string('note')->nullable();
            $table->float('debt_to_vendors')->nullable();
            $table->float('total_price_sell')->default(0);
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();


            $table->foreign('invoice_id')->references('id')->on('invoice');
            $table->foreign('from_bank')->references('id')->on('bank');
            $table->foreign('category_id')->references('id')->on('products');
            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_d');
    }
};
