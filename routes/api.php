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

Route::get('comic-by-slug/{comic:slug}/main-info', ComicMainInfoController::class);
Route::get('comics/{comic}/main-info', ComicMainInfoController::class);
Route::get('comic-by-slug/{comicSlug}/episodes/{episodeNumber}/main-info', EpisodeMainInfoController::class);
Route::get('comic-by-slug/{comic:slug}/episodes', [ComicEpisodesController::class, 'index']);

Route::get('root-comments-of-commentable/{commentable}', CommentableRootCommentsController::class);
Route::get('comment-replies-with-root/{root}', CommentRepliesWithRootController::class);

Route::get('users/{user}/profile-main-info', UserProfileMainInfoController::class);
Route::get('users/{user}/comments', UserCommentsController::class);

Route::get('comic-user-lists/{comicUserList}/entries', [ComicUserListEntriesController::class, 'index']);
Route::get('catalog', CatalogEntriesController::class);
Route::get('catalog-filters', CatalogFiltersController::class)->middleware('cache.headers:public;max_age=600;etag');;

Route::get('home-comic-cards-sections', HomeComicCardsSectionsController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('current-user', CurrentUserController::class);

    Route::post('comic-user-lists/not-in-a-list/put-comic/{comic}', [ComicUserListEntriesController::class, 'removeComic']);
    Route::post('comic-user-lists/{comicUserListSlug}/put-comic/{comic}', [ComicUserListEntriesController::class, 'moveComic']);

    Route::apiResource('commentables.comments', CommentsController::class)->shallow()->only(['store']);
    Route::apiResource('comments.replies', CommentRepliesController::class)->only('store');
    Route::post('likeables/{likeable}/like', [LikesController::class, 'store']);
    Route::delete('likeables/{likeable}/unlike', [LikesController::class, 'destroy']);
});
