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
        Schema::create('promo_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_id')->constrained('promos')->onDelete('cascade');
            $table->unsignedInteger('type_of_service_id');
            $table->foreign('type_of_service_id')->references('id')->on('type_of_service')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_services');
    }
};
