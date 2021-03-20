<?php

\Illuminate\Support\Facades\Route::apiResources([
    'albums'         => \App\Http\Controllers\AlbumController::class,
    'albums.tracks'  => \App\Http\Controllers\TrackController::class,
    'libraries'      => \App\Http\Controllers\LibraryController::class,
    'users'          => \App\Http\Controllers\UserController::class,
    'users.playlist' => \App\Http\Controllers\PlaylistController::class,
]);

Route::post('/libraries/{library}/scan', \App\Http\Controllers\LibraryController::class . '@scan')
    ->name('libraries.scan');

Route::get('/stream/{track}', \App\Http\Controllers\StreamController::class . '@file')
    ->name('tracks.stream');
