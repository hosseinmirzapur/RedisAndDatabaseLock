<?php
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;



// Task 1
Route::post('add-subject-to-user', [SubjectController::class, 'addSubject']);

// Task 2
Route::post('/short-link', [LinkController::class, 'shortLink']);
Route::get('/alias/{alias}', [LinkController::class, 'readAlias']);
Route::get('/registered-routes', [LinkController::class, 'allLinks']);

