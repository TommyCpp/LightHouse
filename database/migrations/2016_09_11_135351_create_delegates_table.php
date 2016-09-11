<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDelegatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegates', function (Blueprint $table) {
            $table->integer('delegate_id')->comment="使用用户ID";
            $table->integer('delegation_id')->comment="代表团ID";
            $table->integer('seat_id')->comment="席位ID";
            $table->timestamps();

            $table->primary("delegate_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('delegates');
    }
}
