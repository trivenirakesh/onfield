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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',200);
            $table->string('last_name',200);
            $table->string('email',200);
            $table->string('mobile',15);
            $table->string('password');
            $table->string('otp',6)->nullable();
            $table->tinyInteger('is_otp_verify')->comment('0 - Not Verify, 1 - Verify')->default('0');
            $table->timestamp('otp_verified_at')->nullable();
            $table->tinyInteger('is_email_verify')->comment('0 - Not Verify, 1 - Verify')->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('entity_type')->comment("0 - Back Office, 1 - Engineer, 2 - Client, 3 - Vendor");
            $table->tinyInteger('status')->comment('0 - Deactive, 1 - Active');
            $table->integer('role_id')->nullable();
            $table->string('longitude',50)->nullable();
            $table->string('latitude',50)->nullable();
            $table->string('notes',100)->nullable();
            $table->rememberToken();
            $this->timestampColumns($table);
        });
        
        // Call seeder
        Artisan::call('db:seed', [
            '--class' => 'EntitySeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
