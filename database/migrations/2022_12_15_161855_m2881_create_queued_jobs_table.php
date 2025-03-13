<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2881CreateQueuedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public.batch_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('classname');
            $table->boolean('cancelled')->default(false);
            $table->timestamps();
        });

        Schema::create('public.queued_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('batch_id');
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('public.batch_jobs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('public.queued_jobs');
        Schema::drop('public.batch_jobs');
    }
}
