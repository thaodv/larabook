<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PhotoController;

use App\Models\Post;
use App\Models\Like;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return auth()->check() ? view('home') : view('welcome');     
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Testing purpose
Route::view('/profile', 'layouts.base'); 

Route::get('/posts/{post}/likes', function (Post $post) {
    $postReaction = $post->likes->all();
    return $postReaction;
});


Route::prefix('/{user:username}')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('profile');

    Route::get('/posts/create', [PostController::class, 'create']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::prefix('/posts/{post}')->group(function () {
        /**
         * Comments routing
         */
        Route::get('/comments/create', [CommentController::class, 'create'])
                    ->name('comments.create');
        Route::post('/comments', [CommentController::class, 'store'])
                    ->name('comments.store');
        Route::get('/comments/{comment}', [CommentController::class, 'show'])
                    ->name('comments.show');
        Route::get('/comments/{comment}/edit', [CommentController::class, 'edit']);
        Route::put('/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
        
        /**
         * Like routing
         */
        Route::post('/like', [LikeController::class, 'store']);
        Route::delete('/unlike', [LikeController::class, 'destroy']);
    
    
    });

    /** Profile Photos */
    Route::get('/photos', [PhotoController::class, 'index']);
});


