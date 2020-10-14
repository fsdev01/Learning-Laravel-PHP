<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController; 
use App\Http\Controllers\PostsController;

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

// Reference to the "index" method of the "PagesController" Controller
Route::get("/", [PagesController::class,"index"]);
Route::get("/about",[PagesController::class,"about"]);
Route::get("/services",[PagesController::class,"services"]);
Route::resource("posts",PostsController::class);



/*

Route::get('/', function () {
    return view('welcome');
});

Route::get("/about",function(){
    //return view("pages/about");
    return view("pages.about");
});

Route::get("/home",function (){
    return "<h1>Hello World</h1>";
});

// Dynamic Route
Route::get("/users/{id}", function($id){
    return "This is user " . $id;
});

// Dynamic Route
Route::get("/users/{id}/{name}", function($id,$name){
    return "This is user " . $name . " with an id of " . $id;
});
*/

// RESTFUL api
//:post //:delete


Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
