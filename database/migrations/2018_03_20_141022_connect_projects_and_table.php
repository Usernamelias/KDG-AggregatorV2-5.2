<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConnectProjectsAndTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->integer('project_id')->unsigned()->after('zoho_project_id');
    
            $table->foreign('project_id')->references('id')->on('projects');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->dropForeign('tasks_project_id_foreign');
    
            $table->dropColumn('project_id');
        });
    }
}
