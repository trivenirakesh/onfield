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
            $table->bigInteger('booking_id');
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment(' 0= unpaid, 1 = paid, 2 = under_process');
            $this->timestampColumns($table);
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
