<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('phone', 11)->unique();
            $table->string('name');
            $table->string('family');
            $table->bigInteger( 'timestamp')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('news');
            $table->boolean('rools');
            $table->string('title_company', 255)->nullable();
            $table->float('stars', 10, 2)->nullable();
            $table->integer('countStar')->nullable();
            $table->string('url_avatar')->nullable();
            $table->string('email_verify_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_verify_code')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->string('about', 3000)->nullable();
            $table->string('numberFree', 11)->nullable();
            $table->string('numberPey', 11)->nullable();
            $table->string('saateKariFrom', 5)->nullable();
            $table->string('saateKariTo', 5)->nullable();
            $table->string('cash')->nullable();
            $table->string('workLocal')->nullable();
            $table->string('theme')->default('light light-sidebar theme-white');
            $table->unsignedBigInteger('userRole')->index()->nullable();
            $table->foreign('userRole')->references('id')->on('access_levels')->onDelete('cascade');

            $table->unsignedBigInteger('workFieldArticle')->index()->nullable();
            $table->foreign('workFieldArticle')->references('id')->on('article_areas')->onDelete('cascade');
            $table->unsignedBigInteger('workFieldService')->index()->nullable();
            $table->foreign('workFieldService')->references('id')->on('service_areas')->onDelete('cascade');

            $table->unsignedBigInteger('articleBrand_id')->index()->nullable();
            $table->foreign('articleBrand_id')->references('id')->on('article_brands')->onDelete('cascade');
            $table->unsignedBigInteger('serviceBrand_id')->index()->nullable();
            $table->foreign('serviceBrand_id')->references('id')->on('service_brands')->onDelete('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
