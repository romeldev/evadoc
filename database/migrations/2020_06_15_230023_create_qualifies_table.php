<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualifies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id');
            $table->string('faculty_code')->nullable();
            $table->string('school_code');
            $table->string('teacher_code');
            $table->string('course_key');
            // $table->string('course_code');
            // $table->string('course_group');
            $table->decimal('avg')->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('qualifies');
    }
}
