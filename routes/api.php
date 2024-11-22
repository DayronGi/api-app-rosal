<?php

use App\Http\Controllers\AssignedTaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandbookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\jobController;
use App\Http\Controllers\ProductController;

Route::get('/', function() { return Redirect::to('/login'); });

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::controller(TaskController::class)->group(function() {
    Route::get('tasks', 'list');
    Route::get('tasks/create', 'create');
    Route::post('tasks/store', 'store');
    Route::post('tasks/search', 'search');
});

Route::controller(ScheduleController::class)->group(function() {
    Route::get('schedules', 'list');
    Route::get('schedules/{schedule_id}', 'review');
    Route::post('schedules', 'store');
});

Route::controller(AssignedTaskController::class)->group(function() {
    Route::get('assignedtasks', 'list');
    Route::post('assignedtasks/search', 'search');
    Route::get('assignedtasks/{task_id}', 'view');
});

Route::controller(MeetingController::class)->group(function() {
    Route::get('meetings', 'list');
    Route::post('meetings/search', 'search');
    Route::get('meetings/{meeting_id}', 'view');
});

Route::controller(HandbookController::class)->group(function() {
    Route::get('handbooks', 'list');
    Route::post('handbooks/search', 'search');
    Route::get('handbooks/{handbook_id}', 'view');
});

Route::controller(LicenseController::class)->group(function() {
    Route::get('licenses', 'list');
    Route::get('licenses/create', 'create');
    Route::post('licenses', 'store');
    Route::post('licenses/search', 'search');
    Route::get('licenses/{license_id}/edit', 'edit');
    Route::put('licenses{license_id}', 'update');
});

Route::controller(ProductController::class)->group(function() {
    Route::get('products', 'list');
    Route::get('products/create', 'create');
    Route::post('products', 'store');
    Route::post('products/search', 'search');
    Route::get('products/{product_id}/edit', 'edit');
    Route::put('products/{product_id}', 'update');
});

Route::controller(WorkerController::class)->group(function() {
    Route::post('worker/search', 'search');
});

Route::controller(JobController::class)->group(function() {
    Route::post('job/search', 'search');
});

Route::get('/rosal', function () {
    return view('organizational.view');
});

Route::get('/login', function () {

    
    return view('login.view');
});