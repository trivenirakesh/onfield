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
            $table->bigInteger('client_id')->comment('users table id');
            $table->bigInteger('address_id')->nullable();
            $table->bigInteger('engineer_id')->nullable()->comment('users table id');
            $table->dateTime('booking_datetime')->nullable();
            $table->tinyInteger('booking_type')->default(1)->comment("0=create by back-office,1=create by client");
            $table->tinyInteger('status')->default(0)->comment("0=Pending, 1=Assign, 2=Reassign,3=Complete, 4=Cancel,5= Missed");
            $table->bigInteger('parent_booking_id')->nullable()->comment('store parent booking id if reassign booking');
            $this->timestampColumns($table);
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
