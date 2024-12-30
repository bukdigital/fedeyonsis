<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\indexController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\LeagueController;
use App\Http\Controllers\Admin\LeagueCategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // Role rotaları
    Route::get('role/', [RoleController::class, 'index'])->name('admin.role.index');
    Route::get('role/create', [RoleController::class, 'create'])->name('admin.role.create');
    Route::post('role/create', [RoleController::class, 'store'])->name('admin.role.store');
    Route::get('role/{id}/detail', [RoleController::class, 'detail'])->name('admin.role.detail')->whereNumber('id');
    Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('admin.role.edit')->whereNumber('id');
    Route::post('role/{id}/edit', [RoleController::class, 'update'])->name('admin.role.update')->whereNumber('id');
    Route::delete('role/delete/{id}', [RoleController::class, 'delete'])->name('admin.role.delete')->whereNumber('id');

    // User rotaları
    Route::get('user/', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('user/create', [UserController::class, 'store'])->name('admin.user.store'); // Çakışma düzeltildi
    Route::get('user/{id}/detail', [UserController::class, 'detail'])->name('admin.user.detail')->whereNumber('id');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit')->whereNumber('id');
    Route::post('user/{id}/edit', [UserController::class, 'update'])->name('admin.user.update')->whereNumber('id');
    Route::delete('user/delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete')->whereNumber('id');

    Route::get('league/', [LeagueController::class, 'index'])->name('admin.league.index');
    Route::get('league/create', [LeagueController::class, 'create'])->name('admin.league.create');
    Route::post('league/create', [LeagueController::class, 'store'])->name('admin.league.store'); // Çakışma düzeltildi
    Route::get('league/{id}/detail', [LeagueController::class, 'detail'])->name('admin.league.detail')->whereNumber('id');
    Route::get('league/{id}/edit', [LeagueController::class, 'edit'])->name('admin.league.edit')->whereNumber('id');
    Route::post('league/{id}/edit', [LeagueController::class, 'update'])->name('admin.v.update')->whereNumber('id');
    Route::delete('league/delete/{id}', [LeagueController::class, 'delete'])->name('admin.league.delete')->whereNumber('id');

    Route::get('season/', [SeasonController::class, 'index'])->name('admin.season.index');
    Route::get('season/create', [SeasonController::class, 'create'])->name('admin.season.create');
    Route::post('season/create', [SeasonController::class, 'store'])->name('admin.season.store'); // Çakışma düzeltildi
    Route::get('season/{id}/detail', [SeasonController::class, 'detail'])->name('admin.season.detail')->whereNumber('id');
    Route::get('season/{id}/edit', [SeasonController::class, 'edit'])->name('admin.season.edit')->whereNumber('id');
    Route::post('season/{id}/edit', [SeasonController::class, 'update'])->name('admin.season.update')->whereNumber('id');
    Route::delete('season/delete/{id}', [SeasonController::class, 'delete'])->name('admin.season.delete')->whereNumber('id');

    Route::resource('league-categories', LeagueCategoryController::class);
    Route::post('league-categories/{category}/toggle-status',[LeagueCategoryController::class, 'toggleStatus'])
            ->name('league-categories.toggle-status');


});

require __DIR__.'/auth.php';
