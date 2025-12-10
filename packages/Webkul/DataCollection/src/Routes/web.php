<?php

use Illuminate\Support\Facades\Route;
use Webkul\DataCollection\Http\Controllers\DataCollectionController;

Route::group([
    'middleware' => ['web'],
    'prefix'     => 'dataCollection'
], function () {

    Route::get('/', [DataCollectionController::class, 'showForm'])
        ->name('dataCollection.form');

    Route::post('/submit', [DataCollectionController::class, 'submit'])
        ->name('dataCollection.submit');

    Route::get('/verify/{token}', [DataCollectionController::class, 'verify'])
        ->name('dataCollection.verify');
});

Route::prefix('dataCollection')->group(function () {
    Route::post('/submit', [DataCollectionController::class, 'submit']);
});
