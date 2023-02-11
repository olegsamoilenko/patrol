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
    public function up()
    {
        Schema::create('incidents', static function (Blueprint $table) {
            $table->id();
            $table->integer('district_id')->unsigned();
            $table->string('address');
            $table->string('name')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_type_other')->nullable();
            $table->string('document')->nullable();
            $table->string('car_number')->nullable();
            $table->string('car_type')->nullable();
            $table->string('car_brand')->nullable();
            $table->string('car_model')->nullable();
            $table->string('car_color')->nullable();
            $table->longText('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};
