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
        Schema::create('character_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_type', 64);
            $table->timestamps();

            $table->string('description', 64);
            $table->unsignedBigInteger('comic_id');
            $table->unsignedBigInteger('character_id');

            //Персонаж может иметь только одну роль в комиксе
            $table->unique(['character_id', 'comic_id'], 'comicchrroles_chr_comics_uix');
            //Достать все комиксы, где конкретный персонаж занимает главную роль (role=main)
            $table->index(['character_id', 'role_type', 'comic_id'], 'comicchrroles_chr_role_comics_ix');
            //Достать всех главных персонажей (role=main) для конкретного комикса
            $table->index(['comic_id', 'role_type', 'character_id'], 'comicchrroles_comic_role_chr_ix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_roles');
    }
};
