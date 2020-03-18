<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone', 11)->unique();
            $table->string('number', 11)->unique();
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            $table->string('cash')->nullable();
            $table->string('email')->nullable()->unique();

            $table->unsignedBigInteger('state_id')->index();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->unsignedBigInteger('city_id')->index();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('district_id')->index()->nullable();
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');

            $table->boolean('active');
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
        Schema::dropIfExists('customers');
    }
}
