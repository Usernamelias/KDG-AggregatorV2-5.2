<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->increments('id');   
            $table->string('project_name');
            $table->string('task');
            $table->time('start_time')->format('h:i A')->nullable();
            $table->time('end_time')->format('h:i A')->nullable();
            $table->integer('duration')->nullable();
            $table->boolean('billable');
            $table->text('description');
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
        Schema::dropIfExists('time_entries');
    }
}
