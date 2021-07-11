<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MarksController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students',[StudentController::class, 'index']);
Route::get('/students-data',[StudentController::class, 'fetchStudents']);
Route::get('/create',[StudentController::class, 'create']);
Route::post('/save',[StudentController::class, 'store']);
Route::get('/edit',[StudentController::class, 'edit']);
Route::post('/delete',[StudentController::class, 'destroy']);
Route::post('/update',[StudentController::class, 'update']);
    
Route::get('/marks/list', [MarksController::class, 'list'])->name('marklist');
Route::get('/marks/add', [MarksController::class, 'add']);
Route::post('/marks/save', [MarksController::class, 'saveMarks']);
Route::get('/marks/term-subjects', [MarksController::class, 'termSubjects']);
Route::get('/marks/edit/{stdid}/{termid}', [MarksController::class, 'editMarks']);
Route::post('/marks/update', [MarksController::class, 'marksUpdate']);
Route::post('/marks/delete', [MarksController::class, 'marksDelete']);


