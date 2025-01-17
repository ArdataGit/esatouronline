<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('invoice_id');
            $table->string('name');
            $table->float('nominal');
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();

            $table->foreign('invoice_id')->references('id')->on('invoice');
            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tax');
    }
};
