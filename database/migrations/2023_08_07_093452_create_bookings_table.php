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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->comment('users table id');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('engineer_id')->nullable()->comment('users table id');
            $table->unsignedBigInteger('service_category_id')->nullable();
            $table->dateTime('booking_start_datetime')->nullable();
            $table->dateTime('booking_end_datetime')->nullable();
            $table->tinyInteger('booking_type')->default(1)->comment("0=create by back-office,1=create by client");
            $table->tinyInteger('status')->default(0)->comment("0=Pending, 1=Assign, 2=Reassign,3=Complete, 4=Cancel,5= Missed");
            $table->unsignedBigInteger('parent_booking_id')->nullable()->comment('store parent booking id if reassign booking');
            $this->timestampColumns($table);
            $table->foreign('client_id')->references('id')->on('users');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('engineer_id')->references('id')->on('users');
            $table->foreign('service_category_id')->references('id')->on('service_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
