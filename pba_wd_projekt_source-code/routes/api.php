<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Api rute til at få alle billederne fra databasen som json
Route::get("/images", [ApiController::class, "apiImageRoute"]);
//Api rute til at uploade nye billeder
Route::post("/image", [ApiController::class, "apiImageUploadRoute"]);
//Api rute til at opdatere sidens indstillinger
Route::post("/settings", [ApiController::class, "apiSettingsRoute"]);
//Api rute til at gemme layouts
Route::post("/edit", [ApiController::class, "apiAdminRoute"]);
//Api rute så vue applikationerne kan få layouts
Route::get("/{route?}", [ApiController::class, "apiRoute"]);