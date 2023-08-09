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
        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable()->comment('leave null if all_day is true');
            $table->time('end_time')->nullable()->comment('leave null if all_day is true');
            $table->unsignedBigInteger('user_id')->nullable()->comment('If null then company schedule');
            $table->boolean('all_day')->default(1)->comment('0 - no, 1 - yes');
            $this->timestampColumns($table);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_exceptions');
    }
};
