<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Results extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('branch')->nullable();
            $table->string('iterations_total')->nullable();
            $table->string('iterations_failed')->nullable();
            $table->string('items_total')->nullable();
            $table->string('items_failed')->nullable();
            $table->string('scripts_total')->nullable();
            $table->string('scripts_failed')->nullable();
            $table->string('prerequests_total')->nullable();
            $table->string('prerequests_failed')->nullable();
            $table->string('requests_total')->nullable();
            $table->string('requests_failed')->nullable();
            $table->string('tests_total')->nullable();
            $table->string('tests_failed')->nullable();
            $table->string('assertions_total')->nullable();
            $table->string('assertions_failed')->nullable();
            $table->string('testScripts_total')->nullable();
            $table->string('testScripts_failed')->nullable();
            $table->string('prerequestScripts_total')->nullable();
            $table->string('prerequestScripts_failed')->nullable();
            $table->string('responseAverage')->nullable();
            $table->string('responseMin')->nullable();
            $table->string('responseMax')->nullable();
            $table->string('started')->nullable();
            $table->string('completed')->nullable();
            $table->string('duration')->nullable();
            $table->text('parsed_results')->nullable();
            $table->text('raw_data')->nullable();
            $table->timestamps();

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
