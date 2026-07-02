<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropForeign(['id_service']);
            $table->dropColumn('id_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->unsignedInteger('id_service')->nullable();
            $table->foreign('id_service')->references('id')->on('type_of_service')->onDelete('cascade');
        });
    }
};
