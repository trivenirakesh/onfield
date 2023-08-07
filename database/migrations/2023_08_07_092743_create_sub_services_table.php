<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

return new class extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('unit_of_measurement');
            $table->tinyInteger('client_requested_qty_is_require')->default(0)->comment('0 = Not Require, 1 = Require');
            $this->timestampColumns($table); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_services');
    }
};
