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
        Schema::create('comics', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 256);
            $table->string('slug', 512)->nullable();
            $table->string('description', 512);

            $table->dateTime('publishing_start');
            $table->dateTime('publishing_end')->nullable();

            $table->timestamps();

            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('publication_status_id');

            $table->index('publication_status_id');
            $table->unique('slug');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comics');
    }
};
