<?php

use Illuminate\Http\Request;
use function Laravel\Prompts\text;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Create\KRSController;
use App\Http\Controllers\Read\SingleReadController;
use App\Http\Controllers\Create\BulkInsertController;
use App\Http\Controllers\Update\BulkUpdateController;
use App\Http\Controllers\Create\SingleInsertController;
use App\Http\Controllers\Delete\SingleDeleteController;
use App\Http\Controllers\Read\RelationalReadController;
use App\Http\Controllers\Update\SingleUpdateController;
use App\Http\Controllers\Read\ConditionalReadController;
use App\Http\Controllers\Create\RelationalInsertController;
use App\Http\Controllers\Delete\RelationalDeleteController;
use App\Http\Controllers\Update\RelationalUpdateController;
use App\Http\Controllers\Delete\ConditionalDeleteController;

Route::get('/user', function (Request $request) {
    return 'text';
});

Route::post('/krs/register', [KRSController::class, 'registerToCourse']);

Route::prefix('single-insert')->group(function () {
    Route::post('/eloquent', [SingleInsertController::class, 'insertSingleMahasiswaWithEloquent'])->name('single.insert.eloquent');
    Route::post('/query-builder', [SingleInsertController::class, 'insertSingleMahasiswaWithQueryBuilder'])->name('single.insert.queryBuilder');
    Route::post('/raw-sql', [SingleInsertController::class, 'insertSingleMahasiswaWithRawSQL'])->name('single.insert.rawSQL');
});

Route::prefix('bulk-insert')->group(function () {
    Route::post('/eloquent', [BulkInsertController::class, 'bulkInsertMahasiswaWithEloquent'])->name('bulk.insert.eloquent');
    Route::post('/query-builder', [BulkInsertController::class, 'bulkInsertMahasiswaWithQueryBuilder'])->name('bulk.insert.queryBuilder');
    Route::post('/raw-sql', [BulkInsertController::class, 'bulkInsertMahasiswaWithRawSQL'])->name('bulk.insert.rawSQL');
});

Route::prefix('relational-insert')->group(function () {
    Route::post('/eloquent', [RelationalInsertController::class, 'insertRelationalWithEloquent'])->name('relational.insert.eloquent');
    Route::post('/query-builder', [RelationalInsertController::class, 'insertRelationalWithQueryBuilder'])->name('relational.insert.queryBuilder');
    Route::post('/raw-sql', [RelationalInsertController::class, 'insertRelationalWithRawSQL'])->name('relational.insert.rawSQL');
});

Route::prefix('single-read')->group(function () {
    Route::get('/eloquent/{id}', [SingleReadController::class, 'getSingleMahasiswaWithEloquent'])->name('single.read.eloquent');
    Route::get('/query-builder/{id}', [SingleReadController::class, 'getSingleMahasiswaWithQueryBuilder'])->name('single.read.queryBuilder');
    Route::get('/raw-sql/{id}', [SingleReadController::class, 'getSingleMahasiswaWithRawSQL'])->name('single.read.rawSQL');
});

Route::prefix('conditional-read')->group(function () {
    Route::get('/eloquent', [ConditionalReadController::class, 'getMahasiswaByJurusanWithEloquent'])->name('conditional.read.eloquent');
    Route::get('/query-builder', [ConditionalReadController::class, 'getMahasiswaByJurusanWithQueryBuilder'])->name('conditional.read.queryBuilder');
    Route::get('/raw-sql', [ConditionalReadController::class, 'getMahasiswaByJurusanWithRawSQL'])->name('conditional.read.rawSQL');
});

Route::prefix('relational-read')->group(function () {
    Route::get('/eloquent/{id}', [RelationalReadController::class, 'getMataKuliahByMahasiswaWithEloquent'])->name('relational.read.eloquent');
    Route::get('/query-builder/{id}', [RelationalReadController::class, 'getMataKuliahByMahasiswaWithQueryBuilder'])->name('relational.read.queryBuilder');
    Route::get('/raw-sql/{id}', [RelationalReadController::class, 'getMataKuliahByMahasiswaWithRawSQL'])->name('relational.read.rawSQL');
});

Route::prefix('single-update')->group(function () {
    Route::put('/eloquent/{id}', [SingleUpdateController::class, 'updateMahasiswaWithEloquent'])->name('update.single.eloquent');
    Route::put('/query-builder/{id}', [SingleUpdateController::class, 'updateMahasiswaWithQueryBuilder'])->name('update.single.queryBuilder');
    Route::put('/raw-sql/{id}', [SingleUpdateController::class, 'updateMahasiswaWithRawSQL'])->name('update.single.rawSQL');
});

Route::prefix('bulk-update')->group(function () {
    Route::put('/eloquent', [BulkUpdateController::class, 'bulkUpdateWithEloquent'])->name('update.bulk.eloquent');
    Route::put('/query-builder', [BulkUpdateController::class, 'bulkUpdateMahasiswaWithQueryBuilder'])->name('update.bulk.queryBuilder');
    Route::put('/raw-sql', [BulkUpdateController::class, 'bulkUpdateMahasiswaWithRawSQL'])->name('update.bulk.rawSQL');
});

Route::prefix('relational-update')->group(function () {
    Route::put('/eloquent/{id}', [RelationalUpdateController::class, 'updateRelationalWithEloquent'])->name('update.relational.eloquent');
    Route::put('/query-builder/{id}', [RelationalUpdateController::class, 'updateRelationalWithQueryBuilder'])->name('update.relational.queryBuilder');
    Route::put('/raw-sql/{id}', [RelationalUpdateController::class, 'updateRelationalWithRawSQL'])->name('update.relational.rawSQL');
});

Route::prefix('single-delete')->group(function () {
    Route::delete('/eloquent/{id}', [SingleDeleteController::class, 'deleteMahasiswaWithEloquent'])->name('delete.single.eloquent');
    Route::delete('/query-builder/{id}', [SingleDeleteController::class, 'deleteMahasiswaWithQueryBuilder'])->name('delete.single.queryBuilder');
    Route::delete('/raw-sql/{id}', [SingleDeleteController::class, 'deleteMahasiswaWithRawSQL'])->name('delete.single.rawSQL');
});

Route::prefix('conditional-delete')->group(function () {
    Route::delete('/eloquent', [ConditionalDeleteController::class, 'deleteByConditionWithEloquent'])->name('delete.conditional.eloquent');
    Route::delete('/query-builder', [ConditionalDeleteController::class, 'deleteByConditionWithQueryBuilder'])->name('delete.conditional.queryBuilder');
    Route::delete('/raw-sql', [ConditionalDeleteController::class, 'deleteByConditionWithRawSQL'])->name('delete.conditional.rawSQL');
});

Route::prefix('relational-delete')->group(function () {
    Route::delete('/eloquent', [RelationalDeleteController::class, 'deleteRelationalWithEloquent'])->name('delete.relational.eloquent');
    Route::delete('/query-builder', [RelationalDeleteController::class, 'deleteRelationalWithQueryBuilder'])->name('delete.relational.queryBuilder');
    Route::delete('/raw-sql', [RelationalDeleteController::class, 'deleteRelationalWithRawSQL'])->name('delete.relational.rawSQL');
});
