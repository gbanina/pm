<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProjects extends Migration
{
    /**
     * Run the migrations.
     * @table projects
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('internal_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->integer('project_type_id')->unsigned();
            $table->string('name', 500);
            $table->integer('default_responsible')->unsigned();
            $table->enum('archived', ['YES', 'NO'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('default_responsible', 'fk_projects_users1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('project_type_id', 'fk_projects_project_types1_idx')
                ->references('id')->on('project_types')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('account_id', 'fk_projects_owners1_idx')
                ->references('id')->on('accounts')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('projects');
     }
}
