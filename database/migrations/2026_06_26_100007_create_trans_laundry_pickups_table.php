<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trans_laundry_pickup', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_order');
            $table->foreign('id_order')->references('id')->on('trans_order');
            $table->unsignedInteger('id_customer');
            $table->foreign('id_customer')->references('id')->on('customer');
            $table->dateTime('pickup_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_laundry_pickup');
    }
};
