<?php

use Illuminate\Http\Request;

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

// =======USER=======
// Check in
Route::get('/getToken/{username}/{b64password}', 'APICheckIn@get_token');
Route::get('/checkIn/{username}/{token}', 'APICheckIn@check_in');

// Login
Route::get('/login/{username}/{b64password}', 'APIUser@login');
// Logout
Route::get('/logout', 'APIUser@logout')
      ->middleware('apicheck.auth');

// Purchase
Route::get('/purchase/{gid}', 'APIUser@purchase')
      ->middleware('apicheck.auth');

// =======ADMIN=======
// Conpensate
Route::get('/admin/conpensate/{uid}/{count}', 'APIAdmin@conpensate')
      ->middleware('apicheck.auth')
      ->middleware('apicheck.admin.auth');
// Activity
  // add
Route::get('/admin/activity/add/{starttime}/{endtime}/{min}/{max}', 'APIAdmin@activity_add')
      ->middleware('apicheck.auth')
      ->middleware('apicheck.admin.auth');
// Goods
  // add
Route::get('/admin/goods/add/{name}/{cost}/{starttime}/{endtime}/{tid}/{sid}/{rebuy}/{all_count}/{description}/{image}', 'APIAdmin@goods_add')
      ->middleware('apicheck.auth')
      ->middleware('apicheck.admin.auth');
