<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MotifiedUserArchives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('FirstName')->nullable();
            $table->string('LastName')->nullable();
            $table->string('HighSchool')->nullable();
            $table->string('University')->nullable();
            $table->enum('Identity', ['Admin', 'Dais', 'AT', 'OT', 'DIR', 'CoreDIR', 'VOL', 'DEL', 'HeadDel','Other']);
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
        //
    }
}
