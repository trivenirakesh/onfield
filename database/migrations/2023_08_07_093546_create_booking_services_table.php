<?php

use App\Traits\CommonTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use CommonTrait;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('sub_service_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('input_from_client')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable()->comment('unit_of_measurement_id');
            $this->timestampColumns($table);
            $table->foreign('sub_service_id')->references('id')->on('sub_services');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
