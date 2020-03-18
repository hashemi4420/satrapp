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
Route::GET('home', 'api\product@home');
Route::GET('articlePageBrand', 'api\product@article');
Route::GET('servicePageBrand', 'api\product@service');

Route::GET('brandHeaderArticle', 'api\product@articleBrandHeader');
Route::GET('brandBodyArticle', 'api\product@articleBrandBody');

Route::GET('serviceHeaderArticle', 'api\product@serviceBrandHeader');
Route::GET('serviceBodyArticle', 'api\product@serviceBrandBody');

Route::GET('article', 'api\product@articlePage');
Route::GET('service', 'api\product@servicePage');

Route::GET('reseller', 'api\product@reseller');
Route::GET('freeCall', 'api\product@freeCall');
Route::GET('sagest', 'api\product@sagest');

Route::GET('callHistory', 'api\product@sagest');

Route::GET('headerReseller', 'api\product@headerReseller');
Route::GET('collectionResellerArticle', 'api\product@collectionResellerArticle');
Route::GET('collectionResellerService', 'api\product@collectionResellerService');
Route::GET('productResellerArticle', 'api\product@productResellerArticle');
Route::GET('productResellerService', 'api\product@productResellerService');

Route::GET('rate', 'api\product@starRate');
Route::GET('sign', 'api\product@sign');
Route::GET('shown', 'api\product@shown');

Route::GET('userRegister', 'api\product@register');
Route::GET('userInformation', 'api\product@information');

Route::GET('state', 'api\product@state');
Route::GET('city', 'api\product@city');
Route::GET('district', 'api\product@district');

Route::GET('reportList', 'api\product@reportQuestion');
Route::GET('report', 'api\product@report');
Route::GET('contact', 'api\product@contact');
Route::GET('search', 'api\product@search');

Route::GET('searchResultArticle', 'api\product@searchResultArticle');
Route::GET('searchResultService', 'api\product@searchResultService');
Route::GET('searchResultArticleBrand', 'api\product@searchResultArticleBrand');
Route::GET('searchResultServiceBrand', 'api\product@searchResultServiceBrand');
Route::GET('searchResultBrand', 'api\product@searchResultBrand');
Route::GET('searchResultReseller', 'api\product@searchResultReseller');

Route::GET('requestSecureCall', 'api\product@requestSecureCall');
Route::GET('requestFreeCall', 'api\product@requestFreeCall');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
