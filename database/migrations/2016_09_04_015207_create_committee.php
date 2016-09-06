<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommittee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees',function(Blueprint $table){
            $table->integer('id');
            $table->string('chinese_name');
            $table->string('english_name');
            $table->integer('delegation')->nullable(); //代表制，每个角色/国家代表数量
            $table->integer('number');//额定代表数量
            $table->timestamps();
            
            $table->primary('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('committees');
    }
}
