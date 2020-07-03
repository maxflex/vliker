<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdToReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reported_tasks');
        Schema::create('reported_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reported_task_id');
            $table->foreign('reported_task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->unsignedBigInteger('reported_by_user_id');
            $table->foreign('reported_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['reported_task_id', 'reported_by_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reported_tasks');
    }
}
