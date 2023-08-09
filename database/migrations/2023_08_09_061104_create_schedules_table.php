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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('work_day', ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('user_id')->nullable()->comment('If null then company schedule');
            $table->tinyInteger('status')->default(1)->comment('0 - Deactive, 1 - Active');
            $this->timestampColumns($table);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
