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
        Schema::create('comic_comic_tag', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('comic_id');
            $table->unsignedBigInteger('comic_tag_id');

            $table->index(['comic_id', 'comic_tag_id']);
            $table->unique(['comic_tag_id', 'comic_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comic_comic_tag');
    }
};
