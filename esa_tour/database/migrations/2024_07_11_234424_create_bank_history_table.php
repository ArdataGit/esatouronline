<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->date('date');
            $table->string('transaction_name');
            $table->string('type');
            $table->float('nominal');
            $table->string('note')->nullable();
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();


            $table->foreign('bank_id')->references('id')->on('bank');
            $table->foreign('invoice_id')->references('id')->on('invoice');
            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_history');
    }
};
