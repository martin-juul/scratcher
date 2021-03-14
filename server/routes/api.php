<?php

\Illuminate\Support\Facades\Route::apiResources([
    'albums'         => \App\Http\Controllers\AlbumController::class,
    'albums.tracks'  => \App\Http\Controllers\TrackController::class,
    'users'          => \App\Http\Controllers\UserController::class,
    'users.playlist' => \App\Http\Controllers\PlaylistController::class,
]);

Route::get('/stream/{track}', \App\Http\Controllers\StreamController::class . '@file')
    ->name('tracks.stream');
