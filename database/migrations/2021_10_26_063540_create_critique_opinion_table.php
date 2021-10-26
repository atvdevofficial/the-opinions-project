<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritiqueOpinionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critique_opinion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('critique_id');
            $table->unsignedBigInteger('opinion_id');
            $table->timestamps();

            $table->foreign('critique_id')->references('id')->on('critiques')->onDelete('cascade');
            $table->foreign('opinion_id')->references('id')->on('opinions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critique_opinion');
    }
}
