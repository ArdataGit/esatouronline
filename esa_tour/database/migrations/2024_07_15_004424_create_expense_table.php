<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('category_expense_id');
            $table->string('name');
            $table->float('nominal');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->longText('note')->nullable();
            $table->foreign('bank_id')->references('id')->on('bank');
            $table->foreign('category_expense_id')->references('id')->on('category_expense');
            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};
