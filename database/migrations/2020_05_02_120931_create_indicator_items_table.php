<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id');
            $table->foreignId('item_id');

            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('indicator_id')->references('id')->on('indicators')
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
        Schema::dropIfExists('indicator_items');
    }
}
