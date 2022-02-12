<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () {
    Route::group(['prefix' => 'parser'], function () {
        Route::post('/', 'ParserController@parserYcombinator')->name('parser.site');
        Route::get('add', 'ParserController@addYcombinator')->name('parser.site.add');
        Route::get('update-points', 'ParserController@updatePointsYcombinator')->name('parser.site.update-points');
        Route::get('update-point/{idItem}', 'ParserController@updatePointYcombinator')->name('parser.site.update-point');
    });
    Route::post('sites', 'ParserController@index')->name('parser.index');
});
