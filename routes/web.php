<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
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

// Main controller
Route::get('/login', [MainController::class, 'formLogin'])->name('login');
Route::get('/register', [MainController::class, 'formRegister'])->name('register');

Route::middleware(['throttle:auth'])->group(function () {
    Route::post('/login', [MainController::class, 'login'])->name('login');
    Route::post('/register', [MainController::class, 'register'])->name('register');
});
Route::middleware(['throttle:global', 'auth'])->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/logout', [MainController::class, 'logout'])->name('logout');
});

Route::get('/locked', [MainController::class, 'locked'])->middleware(['auth', 'throttle:global'])->name('locked');

Route::get('/profile', [MainController::class, 'profile'])->middleware(['auth', 'throttle:global'])->name('profile');
Route::post('/profile/edit', [MainController::class, 'editProfile'])->middleware(['auth', 'throttle:global'])->name('profile.edit');
Route::post('/profile/password', [MainController::class, 'editPassword'])->middleware(['auth', 'throttle:global'])->name('profile.password');



// User controller
Route::middleware(['throttle:global', 'auth', 'not-locked'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/cours', [UserController::class, 'cours'])->name('cours');
    Route::get('/user/cour/{id}', [UserController::class, 'cour'])->name('user.cour');
    Route::get('/user/cour/{id}/inscription', [UserController::class, 'inscription'])->name('user.cour.inscription');
    Route::get('/user/cour/{id}/desinscription', [UserController::class, 'desinscription'])->name('user.cour.desinscription');
});

// Enseignant controller
Route::middleware(['throttle:global', 'auth', 'prof'])->group(function () {
   Route::get('/prof', [EnseignantController::class, 'index'])->name('prof');
   Route::get('/prof/cour/{id}', [EnseignantController::class, 'cour'])->name('prof.cour');
   Route::post('/prof/cour/create', [EnseignantController::class, 'createCour'])->name('prof.cour.create');
   Route::get('/prof/cour/{id}/edit', [EnseignantController::class, 'modifyCourForm'])->name('prof.cour.edit');
   Route::post('/prof/cour/edit', [EnseignantController::class, 'modifyCour'])->name('prof.cour.edit.post');
   Route::get('/prof/cour/delete/{id}', [EnseignantController::class, 'deleteCour'])->name('prof.cour.delete');
   Route::get('/prof/planning', [EnseignantController::class, 'planning'])->name('prof.planning');
});

// Admin controller
Route::middleware(['throttle:global', 'auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    //Gestion des utilisateurs
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.user.create');
    Route::get('/admin/users/{id}/approve', [AdminController::class, 'approveUserForm'])->name('admin.user.approve');
    Route::post('/admin/users/approve', [AdminController::class, 'approveUser'])->name('admin.user.approve.post');
    Route::get('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'modifyUserForm'])->name('admin.user.edit');
    Route::post('/admin/users/edit', [AdminController::class, 'modifyUser'])->name('admin.user.edit.post');
    //Gestion des formations
    Route::get('/admin/formations', [AdminController::class, 'formations'])->name('admin.formations');
    Route::post('/admin/formations/create', [AdminController::class, 'createFormation'])->name('admin.formation.create');
    Route::get('/admin/formations/{id}/edit', [AdminController::class, 'modifyFormationForm'])->name('admin.formation.edit');
    Route::post('/admin/formations/edit', [AdminController::class, 'modifyFormation'])->name('admin.formation.edit.post');
    Route::get('/admin/formations/delete/{id}', [AdminController::class, 'deleteFormation'])->name('admin.formation.delete');
    //Gestion des cours
    Route::get('/admin/cours', [AdminController::class, 'cours'])->name('admin.cours');
    Route::post('/admin/cours/create', [AdminController::class, 'createCour'])->name('admin.cour.create');
    Route::get('/admin/cours/{id}/edit', [AdminController::class, 'modifyCourForm'])->name('admin.cour.edit');
    Route::post('/admin/cours/edit', [AdminController::class, 'modifyCour'])->name('admin.cour.edit.post');
    Route::get('/admin/cours/delete/{id}', [AdminController::class, 'deleteCour'])->name('admin.cour.delete');
    //Gestion des plannings
    Route::get('/admin/cours/{id}', [AdminController::class, 'cour'])->name('admin.cour.planning');
    Route::post('/admin/cours/planning/create', [AdminController::class, 'planningCreate'])->name('admin.planning.create');
    Route::get('/admin/cours/planning/edit/{id}', [AdminController::class, 'planningEditForm'])->name('admin.planning.edit');
    Route::post('/admin/cours/planning/edit', [AdminController::class, 'planningEdit'])->name('admin.planning.edit.post');
    Route::get('/admin/cours/planning/{id}/delete', [AdminController::class, 'planningDelete'])->name('admin.planning.delete');
});
