<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialController;
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

Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('home');


Route::get('login', [AuthController::class, 'login'])->name('login');

// // Google
// Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
// Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.redirect');

// // Facebook
// Route::get('/login/facebook', [AuthController::class, 'redirectToFacebook'])->name('login.facebook');
// Route::get('/login/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('login.facebook.redirect');

// // Github
// Route::get('/login/github', [AuthController::class, 'redirectToGithub'])->name('login.github');
// Route::get('/login/github/callback', [AuthController::class, 'handleGithubCallback'])->name('login.github.redirect');


Route::get('/login/{provider}', [SocialController::class, 'redirectToProvider']);
Route::get('/login/{provider}/callback', [SocialController::class, 'handleProvideCallback']);

Route::get('logout', [SocialController::class, 'logout'])->name('logout');
