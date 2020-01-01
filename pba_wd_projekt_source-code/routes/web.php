<?php

use App\Http\Controllers\RoutesController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

//Ruter til logind, logud, ny bryger, glemt kodeord
Auth::routes();

//Ruter der er beskyttet med logind
Route::middleware("web")->group(function () {
    //Rute til admin dashboard
    Route::get("/admin", [AdminController::class, "dashboard"]);
    //Rute til admin side editor
    Route::get("/admin/edit", [AdminController::class, "spaAdmin"]);
});

//Ruter til front end vue applikation
Route::get("/{route?}", [RoutesController::class, "route"]);