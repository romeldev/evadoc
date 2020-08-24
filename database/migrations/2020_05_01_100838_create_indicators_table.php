<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('name');
            $table->boolean('editable');
            $table->integer('type_id'); // manual, survey
            $table->float('weight');
            $table->foreignId('evaluation_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('evaluation_id')->references('id')->on('evaluations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators');
    }
}
