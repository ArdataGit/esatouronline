<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('account_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->float('beginning_balance');
            $table->float('income')->default(0);
            $table->float('expense')->default(0);
            $table->float('balance')->default(0);
            $table->unsignedBigInteger('created_user');
            $table->unsignedBigInteger('updated_user')->nullable();

            $table->foreign('account_id')->references('id')->on('customer');
            $table->foreign('created_user')->references('id')->on('users');
            $table->foreign('updated_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank');
    }
};
