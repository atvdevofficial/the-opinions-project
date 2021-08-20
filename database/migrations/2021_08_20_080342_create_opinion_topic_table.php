<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpinionTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opinion_topic', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opinion_id');
            $table->unsignedBigInteger('topic_id');
            $table->timestamps();

            $table->foreign('opinion_id')->references('id')->on('opinions')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opinion_topic');
    }
}
