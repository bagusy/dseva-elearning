<?php

use App\Http\Controllers\Auth\LoginWithGoogleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('auth/google', [LoginWithGoogleController::class, 'redirectToGoogle']);
Route::get('callback/google', [LoginWithGoogleController::class, 'handleCallback']);

Route::middleware('throttle:6,1')->group(function () {
    Auth::routes([
        'verify' => true
    ]);
});


Route::group(['middleware' => ['auth', 'is_active_user', 'verified']], function () {
    Route::get('/on-boarding-company', [CompanyController::class, 'formOnboard']);
    Route::post('/on-boarding-company', [CompanyController::class, 'onboard']);

    Route::get('/on-boarding-profile', [UserController::class, 'formOnboard']);
    Route::post('/on-boarding-profile', [UserController::class, 'onboard']);

    Route::group(['middleware' => ['has_company']], function () {
        Route::group(['middleware' => ['has_profile']], function () {
            Route::get('/home', [HomeController::class, 'index'])->name('home');

            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users/update/role', [UserController::class, 'updateRole']);

            Route::get('/company/employees', [EmployeeController::class, 'index']);
            Route::post('/company/employees', [EmployeeController::class, 'store']);
            Route::delete('/company/employees/{employee}', [EmployeeController::class, 'destroy']);

            Route::get('/company/departments', [DepartmentController::class, 'index']);
            Route::post('/company/departments', [DepartmentController::class, 'store']);
            Route::put('/company/departments/{department}', [DepartmentController::class, 'update']);
            Route::delete('/company/departments/{department}', [DepartmentController::class, 'destroy']);

            Route::get('/reviews', function () {
                return view('uc');
            });

            Route::get('/videos', [VideoController::class, 'index']);
            Route::get('/videos/list', [VideoController::class, 'list']);
            Route::post('/videos/list', [VideoController::class, 'store']);
            Route::put('/videos/list/{video}', [VideoController::class, 'update']);
            Route::delete('/videos/list/{video}', [VideoController::class, 'destroy']);

            Route::resource('quiz', QuizController::class, [
                'only' => ['index', 'show', 'create', 'store', 'destroy']
            ]);
            Route::post('/quiz/{quiz}/items', [QuizController::class, 'addQuestion']);

            Route::resource('courses', CourseController::class, [
                'only' => ['index', 'create', 'store', 'show', 'edit', 'update']
            ]);
            Route::post('courses/{course}/sections', [CourseController::class, 'addSection']);
            Route::post('courses/{course}/sections/{section}/{position}', [CourseController::class, 'updateSection']);
            Route::delete('courses/{course}/sections/{section}', [CourseController::class, 'deleteSection']);

            Route::post('courses/{course}/sections/{section}/videos', [CourseController::class, 'addVideos']);
            Route::post('courses/{course}/sections/{section}/quiz', [CourseController::class, 'addQuiz']);

            Route::get('/monthly-videos', function () {
                return view('uc');
            });
            Route::get('/phishing', function () {
                $phishings = \App\Models\PhishingTemplate::orderByDesc('created_at')->get();
                return view('phising', compact('phishings'));
            });
            Route::get('/training', [CourseController::class, 'training']);
            Route::get('/training/{course}', [CourseController::class, 'trainingDetail']);
            Route::get('/trainings/{courseEnrollment}', [CourseController::class, 'trainingProgress']);
            Route::post('/trainings/{courseEnrollment}', [CourseController::class, 'submitQuiz']);
            Route::get('/trainings/{courseEnrollment}/certificate', [CourseController::class, 'downloadCertificate']);
            Route::post('/training/{course}/start', [CourseController::class, 'startTraining']);

            Route::get('/dashboards', [DashboardController::class, 'index']);
        });
    });
});
