<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comic_user_lists', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 64);
            $table->string('slug', 128);
            $table->string('color', 32);
            $table->timestamps();

            $table->unsignedBigInteger('user_id');

            $table->index(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comic_user_lists');
    }
};
