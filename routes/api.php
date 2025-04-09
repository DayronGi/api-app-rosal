<?php

use App\Http\Controllers\AssignedTaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HandbookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\jobController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProductSectionController;
use App\Http\Controllers\ProductMovementController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/device', 'check');
});

// Route::middleware(['auth.uid'])->group(function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('tasks/create', 'create');
        Route::post('tasks/store', 'store');
        Route::post('tasks/search', 'search');
        Route::get('task_type', 'taskType');
        Route::put('tasks/delete', 'delete');
    });

    Route::controller(ScheduleController::class)->group(function () {
        Route::get('schedules', 'list');
        Route::get('schedules/{schedule_id}', 'review');
        Route::post('schedules/store_reviewed', 'store_reviewed');
    });

    Route::controller(AssignedTaskController::class)->group(function () {
        Route::post('assigned_tasks/search', 'search');
        Route::post('assigned_tasks/store', 'store');
        Route::get('assigned_tasks/{task_id}', 'view');
    });

    Route::controller(MeetingController::class)->group(function () {
        Route::post('meetings/search', 'search');
        Route::get('meetings/{meeting_id}', 'view');
    });

    Route::controller(HandbookController::class)->group(function () {
        Route::post('handbooks/search', 'search');
        Route::get('handbooks/{handbook_id}', 'view');
    });

    Route::controller(LicenseController::class)->group(function () {
        Route::get('licenses/create', 'create');
        Route::post('licenses/store', 'store');
        Route::post('licenses/search', 'search');
        Route::get('licenses/{license_id}/edit', 'edit');
        Route::put('licenses/update', 'update');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::post('products/search', 'search');
    });

    Route::controller(WorkerController::class)->group(function () {
        Route::post('worker/search', 'search');
    });

    Route::controller(JobController::class)->group(function () {
        Route::post('job/search', 'search');
    });

    Route::controller(DepartmentController::class)->group(function () {
        Route::get('department', 'list');
        Route::post('department/search', 'search');
    });

    Route::controller(ProductSectionController::class)->group(function () {
        Route::get('product_section', 'list');
        Route::post('product_section/store', 'store');
        Route::put('product_section/{product_id}/{section}/update', 'update');
        Route::put('product_section/{product_id}/{section}/delete', 'delete');
    });

    Route::controller(ProductMovementController::class)->group(function () {
        Route::get('product_movement', 'list');
        Route::get('product_movement/{movement_id}', 'get');
        Route::post('product_movement/store', 'store');
        Route::put('product_movement/{movement_id}/approve', 'approve');
        Route::put('product_movement/{movement_id}/delete', 'delete');
    });
// });