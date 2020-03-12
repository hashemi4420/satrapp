<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('form_id')->index();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->enum('action', ['مشاهده فرم', 'جستجو', 'ویرایش اطلاعات', 'ایجاد اطلاعات', 'حذف اطلاعات', 'تایید اطلاعات']);
            $table->bigInteger( 'timestamp')->nullable();
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
        Schema::dropIfExists('log_histories');
    }
}
