<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WisataApiController;
use App\Http\Controllers\EventApiController;
use App\Http\Controllers\RatingApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::put('update', [AuthController::class, 'update']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

//==============================================================================================
// Wisata routes
Route::get('/wisata', [WisataApiController::class, 'index'])->name('wisata.index');
Route::get('/wisata/{id}', [WisataApiController::class, 'show'])->name('wisata.show');

// Event routes
Route::get('/event', [EventApiController::class, 'index'])->name('event.index');

//wisata_event with admin
Route::group(['middleware' => 'auth:api'], function (){
    Route::post('/event', [EventApiController::class, 'store'])->name('event.store');
    Route::put('/event/{id}', [EventApiController::class, 'update'])->name('event.update');
    Route::delete('/event/{id}', [EventApiController::class, 'destroy'])->name('event.destroy');
    //wisata
    Route::post('/wisata', [WisataApiController::class, 'store'])->name('wisata.store');
    Route::put('/wisata/{id}', [WisataApiController::class, 'update'])->name('wisata.update');
    Route::delete('/wisata/{id}', [WisataApiController::class, 'destroy'])->name('wisata.destroy');
});

//=========================================================================================================

// Rating routes without auth middleware for index and show
Route::get('/wisata/{id}/ratings', [RatingApiController::class, 'index'])->name('wisata.ratings.index');
Route::get('/wisata/{id}/ratings/{rating_id}', [RatingApiController::class, 'show'])->name('wisata.ratings.show');

// Rating routes with auth middleware for store, update, and destroy
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/wisata/{id}/ratings', [RatingApiController::class, 'store'])->name('wisata.ratings.store');
    Route::put('/wisata/{id}/ratings/{rating_id}', [RatingApiController::class, 'update'])->name('wisata.ratings.update');
    Route::delete('/wisata/{id}/ratings/{rating_id}', [RatingApiController::class, 'destroy'])->name('wisata.ratings.destroy');
});
