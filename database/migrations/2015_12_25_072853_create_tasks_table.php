<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ownerId')->unsigned();
            $table->integer('day');
            $table->string('title');
            $table->text('description');
            $table->date('start_time');
            $table->date('end_time');
            $table->boolean('completed');
            $table->boolean('inProgress');
            $table->string('inProgressBy')->default('');
             $table->string('completedBy')->default('');
             $table->boolean('isLocked')->default(false);

            $table->foreign('ownerId')
            ->references('id')
            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
