<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//login and register routes
Route::get('/register', [AuthController::class, 'register_form']);
Route::get('/login', [AuthController::class, 'login_form']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
//ends here

//Authenticated routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');

    //task route

    Route::get('/task/list', [TaskController::class, 'list'])->name('task.list');
    Route::get('/task/show/{id}', [TaskController::class, 'show'])->name('task.show');
    Route::get('/task/add', [TaskController::class, 'add_form'])->name('task.add_form');
    Route::post('/task/add', [TaskController::class, 'add'])->name('task.add');
    Route::get('/task/edit/{id}', [TaskController::class, 'update_form'])->name('task.edit');
    Route::post('/task/update/{id}', [TaskController::class, 'update'])->name('task.update');
    Route::post('/task/delete/{id}', [TaskController::class, 'delete'])->name('task.delete');

    //ends here

    //notification

    Route::get('/notification/close', [TaskController::class, 'markAsRead'])->name('notification . close');

    //ends here
});

//ends here