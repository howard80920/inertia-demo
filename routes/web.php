<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers;

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

Auth::routes(['reset' => false]);

Route::get('/', Controllers\Post\ShowPostList::class);

Route::get('/about', function () {
    return Inertia::render('About', [
        // 'name' => $sss,
    ]);
});


// User
Route::get('user/setting', [ UserController::class, 'edit' ]);
Route::put('user', [ UserController::class, 'update' ]);
Route::delete('user', [ UserController::class, 'destroy' ]);

Route::get('user/{user}', [ Controllers\User\ProfileController::class, 'index' ]);
Route::get('user/{user}/likes', [ Controllers\User\ProfileController::class, 'likes' ]);

Route::resource('posts', Controllers\Post\PostController::class)->except('show');
Route::resource('posts.comments', Controllers\Post\CommentController::class)->shallow()->only('store', 'destroy');
Route::get('posts/drafts', [ Controllers\Post\PostController::class, 'drafts' ]);
Route::get('posts/{post}', Controllers\Post\ShowPost::class);
Route::post('posts/{post}/like', [ Controllers\Post\PostController::class, 'like' ]);


// Upload files
Route::post('upload/mavon-editor-image', [ Controllers\UploadController::class, 'mavonEditorImage' ]);
