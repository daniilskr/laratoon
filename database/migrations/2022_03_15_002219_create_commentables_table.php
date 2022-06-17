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
        Schema::create('commentables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('comments_cached_count');
            $table->unsignedInteger('owner_type');

            $table->unique(['owner_id', 'owner_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commentables');
    }
};
