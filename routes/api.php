<?php

use App\Http\Controllers\CatalogEntriesController;
use App\Http\Controllers\CatalogFiltersController;
use App\Http\Controllers\ComicMainInfoController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\EpisodeMainInfoController;
use App\Http\Controllers\ComicEpisodesController;
use App\Http\Controllers\ComicUserListEntriesController;
use App\Http\Controllers\CommentableRootCommentsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\CommentRepliesController;
use App\Http\Controllers\CommentRepliesWithRootController;
use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\HomeComicCardsSectionsController;
use App\Http\Controllers\UserCommentsController;
use App\Http\Controllers\UserProfileMainInfoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/demo-login', [DemoLoginController::class, 'login']);

Route::get('comic-by-slug/{comic:slug}/main-info', ComicMainInfoController::class);
Route::get('comics/{comic}/main-info', ComicMainInfoController::class)->name('comics.main_info');
Route::get('comic-by-slug/{comicSlug}/episodes/{episodeNumber}/main-info', EpisodeMainInfoController::class)->name('comic_by_slug.episode_by_number.main_info');
Route::get('comic-by-slug/{comic:slug}/episodes', [ComicEpisodesController::class, 'index']);

Route::get('root-comments-of-commentable/{commentable}', CommentableRootCommentsController::class)->name('root_comments_of_commentable');
Route::get('comment-replies-with-root/{root}', CommentRepliesWithRootController::class)->name('comment_replies_with_root');

Route::get('users/{user}/profile-main-info', UserProfileMainInfoController::class)->name('users.profile_main_info');
Route::get('users/{user}/comments', UserCommentsController::class)->name('users.comments.show');

Route::get('comic-user-lists/{comicUserList}/entries', [ComicUserListEntriesController::class, 'index']);
Route::get('catalog', CatalogEntriesController::class)->name('catalog');
Route::get('catalog-filters', CatalogFiltersController::class)->middleware('cache.headers:public;max_age=600;etag');

Route::get('home-comic-cards-sections', HomeComicCardsSectionsController::class);

Route::middleware('auth')->group(function () {
    Route::get('current-user', CurrentUserController::class)->name('current_user');

    Route::post('comic-user-lists/not-in-a-list/put-comic/{comic}', [ComicUserListEntriesController::class, 'removeComic']);
    Route::post('comic-user-lists/{comicUserListSlug}/put-comic/{comic}', [ComicUserListEntriesController::class, 'moveComic']);

    Route::apiResource('commentables.comments', CommentsController::class)->shallow()->only(['store']);
    Route::apiResource('comments.replies', CommentRepliesController::class)->only('store');
    Route::post('likeables/{likeable}/like', [LikesController::class, 'store'])->name('likeables.like.store');
    Route::delete('likeables/{likeable}/like', [LikesController::class, 'destroy'])->name('likeables.like.destroy');
});
