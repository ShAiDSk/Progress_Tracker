<?php

use App\Http\Controllers\GoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubTaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes are included via Laravel Breeze
require __DIR__ . '/auth.php';

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {

    // ✅ FIX 1: SPECIFIC ROUTES FIRST (Before resource)
    // Changed URL to avoid conflict with /goals/{id}
    Route::get('/archives', [GoalController::class, 'archived'])->name('goals.archived');

    // Restore archived goal (Keep progress)
    Route::post('/goals/{goal}/restore', [GoalController::class, 'restore'])->name('goals.restore');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ✅ FIX 2: Custom Actions (Cleaned up duplicates)

    // Archive / Hide
    Route::post('/goals/{goal}/archive', [GoalController::class, 'archive'])->name('goals.archive');

    // Reopen
    Route::post('/goals/{goal}/reopen', [GoalController::class, 'reopen'])->name('goals.reopen');

    // Mark as Done (Using 'markDone' to match your controller)
    Route::post('/goals/{goal}/complete', [GoalController::class, 'markDone'])->name('goals.done');

    // Increment / Update Progress
    Route::post('/goals/{goal}/increment', [GoalController::class, 'increment'])->name('goals.increment');
    Route::post('/goals/{goal}/update-progress', [GoalController::class, 'increment'])->name('goals.updateProgress');

    // Batch complete
    Route::post('/goals/batch-complete', [GoalController::class, 'batchComplete'])->name('goals.batch-complete');

    // ✅ FIX 3: RESOURCE ROUTE LAST
    // This catches standard /goals/{id} requests
    Route::resource('goals', GoalController::class);

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Subtasks
    Route::post('/goals/{goal}/subtasks', [SubTaskController::class, 'store'])->name('subtasks.store');
    Route::post('/subtasks/{subTask}/toggle', [SubTaskController::class, 'toggle'])->name('subtasks.toggle');
    Route::delete('/subtasks/{subTask}', [SubTaskController::class, 'destroy'])->name('subtasks.destroy');
});
