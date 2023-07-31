<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unit_of_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->text('description')->nullable();
            $table->decimal('factor', 8, 2);
            $table->tinyInteger('status')->comment('0 - Deactive, 1 - Active');
            $this->timestampColumns($table);
        });
        
        // Call seeder
        Artisan::call('db:seed', [
            '--class' => 'UnitOfMeasurementSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_of_measurements');
    }
};
