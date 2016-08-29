<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserArchives extends Migration
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
            $table->string('HighSchool');
            $table->string('University')->nullable();
            $table->enum('Identity', ['Admin', 'Dais', 'AcademicTeam', 'OrganizingTeam', 'Director', 'CoreDirector', 'Volunteer', 'Teacher', 'Other']);
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
        Schema::drop('UserArchives');
    }
}
