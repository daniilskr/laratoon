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
        Schema::create('comic_user_list_entries', function (Blueprint $table) {
            $table->id('id');
            $table->timestamps();

            $table->unsignedBigInteger('comic_user_list_id');
            $table->unsignedBigInteger('comic_id');
            $table->unsignedBigInteger('user_id');

            //Комикс может находиться только в одном списке пользователя
            $table->unique(['user_id', 'comic_id']);
            //Для просмотра вхождений в списке
            $table->index(['comic_user_list_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comic_user_list_entries');
    }
};
