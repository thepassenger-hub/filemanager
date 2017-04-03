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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function() {
    Route::get('files', 'FilesController@index'); 
    // Route::get('files/{file}', 'FilesController@show');
    Route::put('files', 'FilesController@store');
    Route::delete('files', 'FilesController@destroy');
    Route::patch('files/move', 'FilesController@move');
    Route::put('files/copy', 'FilesController@copy');
    Route::patch('files/rename', 'FilesController@rename');
    Route::patch('files/permission', 'FilesController@permission');
    Route::get('files/open', 'FilesController@open');
    
});
