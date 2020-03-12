<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceCreatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_creators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('url_avatar');
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->foreign('brand_id')->references('id')->on('service_brands')->onDelete('cascade');
            $table->unsignedBigInteger('area_id')->index();
            $table->foreign('area_id')->references('id')->on('service_areas')->onDelete('cascade');
            $table->unsignedBigInteger('group_id')->index();
            $table->foreign('group_id')->references('id')->on('service_groups')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->foreign('category_id')->references('id')->on('service_categories')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('service_creators');
    }
}
