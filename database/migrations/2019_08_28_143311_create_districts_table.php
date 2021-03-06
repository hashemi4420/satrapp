<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->unsignedBigInteger('state_id')->index();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->unsignedBigInteger('city_id')->index();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->bigInteger( 'timestamp')->nullable();
            $table->boolean('active')->default(1);
            $table->enum('accept', ['منتظر تایید', 'تایید شده', 'رد شده'])->default('منتظر تایید');
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
        Schema::dropIfExists('districts');
    }
}
