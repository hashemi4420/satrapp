<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCreatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_creators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('url_avatar');
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->foreign('brand_id')->references('id')->on('article_brands')->onDelete('cascade');
            $table->unsignedBigInteger('area_id')->index()->nullable();
            $table->foreign('area_id')->references('id')->on('article_areas')->onDelete('cascade');
            $table->unsignedBigInteger('group_id')->index()->nullable();
            $table->foreign('group_id')->references('id')->on('article_groups')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->foreign('category_id')->references('id')->on('article_categories')->onDelete('cascade');
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
        Schema::dropIfExists('article_creators');
    }
}
