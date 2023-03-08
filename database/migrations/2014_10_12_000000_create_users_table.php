<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('phone', 100)->unique();
            $table->string('email')->unique();
            $table->integer('formation_id');
            $table->integer('battalion')->nullable();
            $table->integer('company')->nullable();
            $table->integer('platoon')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('is_activated')->default('Ні');
            $table->json('activated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->json('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
