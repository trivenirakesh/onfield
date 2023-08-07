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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('unit_of_measurement_id');
            $table->foreign('unit_of_measurement_id')->references('id')->on('unit_of_measurements');
            $table->unsignedBigInteger('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->tinyInteger('is_vendor')->default(0)->comment('0 - Product, 1 - Vendor Product');;
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('price', 12, 2);
            $table->tinyInteger('status')->comment('0 - Deactive, 1 - Active');
            $this->timestampColumns($table);
        });

        // Call seeder
        Artisan::call('db:seed', [
            '--class' => 'ProductSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
