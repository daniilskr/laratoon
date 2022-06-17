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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id');
            $table->timestamps();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('commentable_id');
            $table->string('comment_text', 512);

            $table->unsignedBigInteger('root_comment_id')->nullable();
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->unsignedBigInteger('root_child_comments_cached_count');

            // Чтобы доставать ответы на корневой комментарий
            $table->index(['root_comment_id']);
            // Чтобы считать комментарии по всем пользователям
            $table->index(['commentable_id']);
            // Чтобы смотреть комментарии конкретного пользователя
            $table->index(['user_id', 'commentable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
