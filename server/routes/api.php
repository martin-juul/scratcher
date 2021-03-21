<?php

Route::group(['prefix' => 'auth', 'middleware' => 'respond.json'], function () {
    Route::post('', \App\Http\Controllers\AuthController::class . '@authenticate')
        ->name('auth.authenticate');

    Route::get('', \App\Http\Controllers\AuthController::class . '@index')
        ->middleware('auth:sanctum')
        ->name('auth.tokens');

    Route::delete('/token/revoke-all', \App\Http\Controllers\AuthController::class . '@revoke')
        ->middleware('auth:sanctum')
        ->name('auth.revokeAll');

    Route::delete('/token/{id}', \App\Http\Controllers\AuthController::class . '@revoke')
        ->middleware('auth:sanctum')
        ->name('auth.revoke');
});

Route::apiResources([
    'albums'        => \App\Http\Controllers\AlbumController::class,
    'albums.tracks' => \App\Http\Controllers\TrackController::class,
    'libraries'     => \App\Http\Controllers\LibraryController::class,
    'users'         => \App\Http\Controllers\UserController::class,
    'playlists'     => \App\Http\Controllers\PlaylistController::class,
], ['middleware' => ['auth:sanctum','respond.json']]);

Route::post('/libraries/{library}/scan', \App\Http\Controllers\LibraryController::class . '@scan')
    ->middleware(['auth:sanctum','respond.json'])
    ->name('libraries.scan');

Route::get('/stream/{track}', \App\Http\Controllers\StreamController::class . '@file')
    ->middleware('auth:sanctum')
    ->name('tracks.stream');
