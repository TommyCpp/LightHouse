<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->increments('seat_id');
            $table->integer('committee_id')->comment="委员会ID";
            $table->string("main_name")->comment="主要名称（双代应使用同一主名称）";
            $table->string("assist_name")->nullable()->comment="辅助名称";
            $table->string("note")->comment="备注（制作国家牌时候用）";
            $table->timestamps();
            
            $table->foreign("committee_id")->references("id")->on("committees");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seats');
    }
}
