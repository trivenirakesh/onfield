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
        Schema::create('service_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->unsignedBigInteger('skill_id')->nullable();
            $table->foreign('skill_id')->references('id')->on('skills');
            $this->timestampColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_skills');
    }
};
