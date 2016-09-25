<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatExchangeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_exchange_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string("initiator")->comment="发起代表团";
            $table->string("target")->comment = "目标代表团";
            $table->integer("committee_id")->comment="委员会ID";
            $table->integer("in")->comment="获得数量";
            $table->integer("out")->comment="送出数量";
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
        Schema::drop('seat_exchange_records');
    }
}
