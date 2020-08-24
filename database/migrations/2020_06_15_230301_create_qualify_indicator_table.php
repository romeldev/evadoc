<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualifyIndicatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualify_indicator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qualify_id');
            $table->foreignId('indicator_id');
            $table->string('value');
            $table->timestamps();

            $table->foreign('qualify_id')->references('id')->on('qualifies');
            $table->foreign('indicator_id')->references('id')->on('indicators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qualify_indicator');
    }
}
