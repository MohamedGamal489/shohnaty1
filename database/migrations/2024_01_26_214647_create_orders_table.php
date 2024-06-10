<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->cascadeOnDelete();

            $table->string('load_street');
            $table->string('load_city');
            $table->string('load_region');
            $table->string('load_state');
            $table->string('load_building_number');
            $table->string('load_lat');
            $table->string('load_lng');

            $table->string('delivery_street');
            $table->string('delivery_city');
            $table->string('delivery_region');
            $table->string('delivery_state');
            $table->string('delivery_building_number');
            $table->string('delivery_lat');
            $table->string('delivery_lng');

            $table->string('status');
            $table->string('price');

            $table->string('payment_method')->default('cash');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
