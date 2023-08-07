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
            $table->bigInteger('booking_id');
            $table->bigInteger('sub_service_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->string('input_from_client')->nullable();
            $table->bigInteger('uom_id')->nullable()->comment('unit_of_measurement_id');
            $this->timestampColumns($table);
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
