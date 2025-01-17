<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->date('date_publisher');
            $table->unsignedBigInteger('physical_invoice_id');
            $table->string('invoice_number');
            $table->string('status')->default('Belum Lunas');
            $table->boolean('is_printed')->default(false);
            $table->float('price_total_selling')->default(0);
            $table->float('price_total_purchase')->default(0);
            $table->float('total_profit')->default(0);
            $table->float('receivables')->default(0);
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();


            $table->foreign('physical_invoice_id')->references('id')->on('physical_invoice');
            $table->foreign('customer_id')->references('id')->on('customer');
            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
