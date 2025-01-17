<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['deposit_id']);
            $table->dropForeign(['asset_id']);
        });
    }

    public function down(): void
    {
        Schema::table('bank_history', function (Blueprint $table) {
            //
        });
    }
};
