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
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('transaction_id')->nullable();
            $table->enum('payment_type', ['digital', 'cash'])->nullable();
            $table->string('note')->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment(' 0= unpaid, 1 = paid, 2 = under_process');
            $this->timestampColumns($table);
            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
