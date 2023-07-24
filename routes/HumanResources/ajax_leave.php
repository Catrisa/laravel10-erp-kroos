<?php
// Continuence from routes/web.php
use App\Http\Controllers\HumanResources\AjaxController;
use Illuminate\Support\Facades\Route;

// Ajax Controller
Route::patch('/leavecancel/{hrleave}', [AjaxController::class, 'update'])->name('leavecancel.update');
Route::post('/leaveType', [AjaxController::class, 'leaveType'])->name('leaveType.leaveType');
Route::post('/backupperson', [AjaxController::class, 'backupperson'])->name('backupperson.backupperson');

// Route::get('/login/{login}', [
// 	'as' => 'login.edit',
// 	'uses' => 'Profile\LoginController@edit'
// ]);
