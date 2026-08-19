<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth');
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::get('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware('auth');
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth');
Route::put('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware(['auth', 'can:admin.roles.edit']);
Route::delete('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware(['auth', 'can:admin.roles.destroy']);

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');
Route::post('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::get('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');
Route::put('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');

// Rutas para Streams / Monitoreo SRT
Route::get('/admin/streams', [App\Http\Controllers\StreamController::class, 'index'])->name('admin.streams.index')->middleware('auth');
Route::get('/admin/streams/create', [App\Http\Controllers\StreamController::class, 'create'])->name('admin.streams.create')->middleware('auth');
Route::post('/admin/streams', [App\Http\Controllers\StreamController::class, 'store'])->name('admin.streams.store')->middleware('auth');
Route::get('/admin/stream/{id}', [App\Http\Controllers\StreamController::class, 'show'])->name('admin.streams.show')->middleware('auth');
Route::get('/admin/stream/{id}/edit', [App\Http\Controllers\StreamController::class, 'edit'])->name('admin.streams.edit')->middleware('auth');
Route::put('/admin/stream/{id}', [App\Http\Controllers\StreamController::class, 'update'])->name('admin.streams.update')->middleware('auth');
Route::delete('/admin/stream/{id}', [App\Http\Controllers\StreamController::class, 'destroy'])->name('admin.streams.destroy')->middleware('auth');

require __DIR__.'/settings.php';
