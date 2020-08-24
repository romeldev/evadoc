<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reply_id');
            $table->foreignId('item_id');
            $table->string('value');
            $table->timestamps();

            $table->foreign('reply_id')->references('id')->on('replies');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reply_items');
    }
}
