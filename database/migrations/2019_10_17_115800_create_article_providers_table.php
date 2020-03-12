<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id')->index()->nullable();
            $table->foreign('article_id')->references('id')->on('article_creators')->onDelete('cascade');
            $table->string('price');
            $table->string('status', 2000)->nullable();
            $table->string('atless');
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
        Schema::dropIfExists('article_providers');
    }
}
