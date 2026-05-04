<?php

use App\Http\Controllers\Api\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('courses/{course}/videos', [CourseController::class, 'videoList']);
    Route::get('courses/{course}/quiz', [CourseController::class, 'quizList']);
});
