<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\TweetLikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\RoomController;       // ◀︎ DM機能: 追加
use App\Http\Controllers\MessageController;   // ◀︎ DM機能: 追加
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール、ツイート、いいね、コメント、フォローなどの既存ルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/tweets/search', [TweetController::class, 'search'])->name('tweets.search');
    Route::resource('tweets', TweetController::class);
    Route::post('/tweets/{tweet}/like', [TweetLikeController::class, 'store'])->name('tweets.like');
    Route::delete('/tweets/{tweet}/like', [TweetLikeController::class, 'destroy'])->name('tweets.dislike');
    Route::resource('tweets.comments', CommentController::class);
    Route::post('/follow/{user}', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user}', [FollowController::class, 'destroy'])->name('follow.destroy');
    
    // 🔽 DM機能のルートを追加 🔽
    // DMルームを作成・既存ルームへリダイレクト
    Route::post('/room/{user}', [RoomController::class, 'store'])->name('room.store');
    // DMルームのメッセージ表示
    Route::get('/room/{room}', [RoomController::class, 'show'])->name('room.show');
    // DMメッセージの送信
    Route::post('/room/{room}/message', [MessageController::class, 'store'])->name('message.store');
    // 🔼 DM機能のルートを追加 🔼
});

require __DIR__.'/auth.php';
