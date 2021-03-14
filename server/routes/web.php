<?php

use Illuminate\Support\Facades\Route;

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
    $fs = new \App\Scanner\FileScanner('/Users/martin/gits/deemix-docker/downloads');
    $files = $fs->listContents();

    dd($fs->parseId3($files->first()));
});

Route::get('/scan', function () {
    $library = \App\Models\Library::first();

    if (!$library) {
        $library = \App\Models\Library::create([
            'name' => 'music',
            'path' => '/Users/martin/gits/deemix-docker/downloads',
        ]);
    }

    $r = new \App\Jobs\LibraryScan($library);

    dump($r->handle());
});
