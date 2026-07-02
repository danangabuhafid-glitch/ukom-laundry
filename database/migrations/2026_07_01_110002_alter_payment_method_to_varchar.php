<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trans_order', function (Blueprint $table) {
            $table->string('payment_method', 50)->default('Cash')->change();
        });
    }

    public function down(): void
    {
        // Can't revert string → enum safely, no-op
    }
};