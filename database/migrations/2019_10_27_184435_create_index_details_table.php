<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_details', function (Blueprint $table) {
            $table->bigIncrements('id');
//            $table->string('homeTitle1');
//            $table->string('homeTitle2');
//            $table->string('homeDetail');
//            $table->string('homeAvatar');
//
//            $table->string('propertyTitle1');
//            $table->string('propertyDetail1');
//
//            $table->string('propertyBoxTitle1');
//            $table->string('propertyBoxDetail1');
//
//            $table->string('propertyBoxTitle2');
//            $table->string('propertyBoxDetail2');
//
//            $table->string('propertyBoxTitle3');
//            $table->string('propertyBoxDetail3');
//
//            $table->string('propertyTitle2');
//            $table->string('propertyDetail2');
//
//            $table->string('propertyNewsTitle1');
//            $table->string('propertyNewsDetail1');
//            $table->string('propertyNewsAvatar1');
//
//            $table->string('propertyNewsTitle2');
//            $table->string('propertyNewsDetail2');
//            $table->string('propertyNewsAvatar2');
//
//            $table->string('propertyNewsTitle3');
//            $table->string('propertyNewsDetail3');
//            $table->string('propertyNewsAvatar3');
//
//            $table->string('propertyNewsTitle4');
//            $table->string('propertyNewsDetail4');
//            $table->string('propertyNewsAvatar4');
//
//            $table->string('propertyNewsTitle5');
//            $table->string('propertyNewsDetail5');
//            $table->string('propertyNewsAvatar5');
//
//            $table->string('propertyNewsTitle6');
//            $table->string('propertyNewsDetail6');
//            $table->string('propertyNewsAvatar6');
//
//            $table->string('appTitle1');
//            $table->string('appTitle2');
//            $table->string('appDetail1');
//            $table->string('appAvatar1');
//            $table->string('appAvatar2');
//            $table->string('appCheck1');
//            $table->string('appCheck2');
//            $table->string('appCheck3');
//
//            $table->string('appTitle3');
//            $table->string('appTitle4');
//            $table->string('appAvatar3');
//            $table->string('appAvatar4');
//
//            $table->string('appCheck4');
//            $table->string('appCheck5');
//            $table->string('appCheck6');
//
//            $table->string('screenTitle1');
//            $table->string('screenDetail1');
//
//            $table->string('screenAvatar1');
//            $table->string('screenAvatar2');
//            $table->string('screenAvatar3');
//            $table->string('screenAvatar4');
//            $table->string('screenAvatar5');
//            $table->string('screenAvatar6');
//            $table->string('screenAvatar7');
//
//            $table->string('screenTitle2');
//            $table->string('screenDetail2');
//
//            $table->string('point');
//            $table->string('pointCount');
//
//            $table->string('starFive');
//            $table->string('starFour');
//            $table->string('starThree');
//            $table->string('starTwo');
//            $table->string('starOne');
//
//            $table->string('QuestionTitle');
//            $table->string('QuestionDetail');
//
//            $table->string('Question1');
//            $table->string('Answer1');
//
//            $table->string('Question2');
//            $table->string('Answer2');
//
//            $table->string('Question3');
//            $table->string('Answer3');
//
//            $table->string('Question4');
//            $table->string('Answer4');
//
//            $table->string('Question5');
//            $table->string('Answer5');
//
//            $table->string('QuestionAvatar');
//
//            $table->string('connectUsTitle');
//            $table->string('connectUsDetail');
//
//            $table->string('connectUsNumber1');
//            $table->string('connectUsNumber2');
//
//            $table->string('connectUsEmail1');
//            $table->string('connectUsEmail2');
//
//            $table->string('connectUsLocal');
//
//            $table->string('footerTitle');
//            $table->string('footerDetail');



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
        Schema::dropIfExists('index_details');
    }
}
