<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelegationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("head_delegate_id");
            $table->string('name')->comment="代表团名称";
            $table->integer('delegate_number')->default(0)->comment = "代表团人数";//代表团人数
            $table->integer('seat_number')->default(0)->comment = "席位总数"; //席位总数
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
        Schema::drop('delegations'); 
    }
}
