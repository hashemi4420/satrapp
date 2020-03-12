<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/', function () {
    return view('index');
});

Route::get('/home', function () {
    return view('index');
});

Auth::routes();

Route::get('/logout', function () {
    if (Auth::check())
    {
        Auth::logout();
    }
    return redirect('/home');
});

Route::middleware('auth')->get('/manage/index', function () {
    return view('manage.index');
});

Route::get('/manage/index', 'IndexController@show');
Route::post('/manage/index/loadData', 'IndexController@loadData');

// کالا
Route::get('/manage/articleBrand', 'ArticleBrandController@show');
Route::post('/manage/articleBrand', 'ArticleBrandController@save');
Route::post('/manage/articleBrand/search', 'ArticleBrandController@search');
Route::post('/manage/articleBrand/startUpdate', 'ArticleBrandController@startUpdate');
Route::post('/manage/articleBrand/Update', 'ArticleBrandController@update');
Route::post('/manage/articleBrand/delete', 'ArticleBrandController@delete');

Route::get('/manage/articleArea', 'ArticleAreaController@show');
Route::post('/manage/articleArea', 'ArticleAreaController@save');
Route::post('/manage/articleArea/search', 'ArticleAreaController@search');
Route::post('/manage/articleArea/startUpdate', 'ArticleAreaController@startUpdate');
Route::post('/manage/articleArea/Update', 'ArticleAreaController@update');
Route::post('/manage/articleArea/delete', 'ArticleAreaController@delete');
Route::post('/manage/articleArea/active', 'ArticleAreaController@active');

Route::get('/manage/articleGroup', 'ArticleGroupController@show');
Route::post('/manage/articleGroup', 'ArticleGroupController@save');
Route::post('/manage/articleGroup/search', 'ArticleGroupController@search');
Route::post('/manage/articleGroup/startUpdate', 'ArticleGroupController@startUpdate');
Route::post('/manage/articleGroup/Update', 'ArticleGroupController@update');
Route::post('/manage/articleGroup/delete', 'ArticleGroupController@delete');

Route::get('/manage/articleCategory', 'ArticleCategoryController@show');
Route::post('/manage/articleCategory', 'ArticleCategoryController@save');
Route::post('/manage/articleCategory/getGroups', 'ArticleCategoryController@getGroups');
Route::post('/manage/articleCategory/search', 'ArticleCategoryController@search');
Route::post('/manage/articleCategory/startUpdate', 'ArticleCategoryController@startUpdate');
Route::post('/manage/articleCategory/Update', 'ArticleCategoryController@update');
Route::post('/manage/articleCategory/delete', 'ArticleCategoryController@delete');

Route::get('/manage/createArticle', 'ArticleCreatorController@show');
Route::post('/manage/createArticle', 'ArticleCreatorController@save');
Route::post('/manage/articleCreator/getGroup', 'ArticleCreatorController@getGroup');
Route::post('/manage/articleCreator/getCategories', 'ArticleCreatorController@getCategories');
Route::post('/manage/articleCreator/search', 'ArticleCreatorController@search');
Route::post('/manage/articleCreator/startUpdate', 'ArticleCreatorController@startUpdate');
Route::post('/manage/articleCreator/Update', 'ArticleCreatorController@update');
Route::post('/manage/articleCreator/delete', 'ArticleCreatorController@delete');
Route::post('/manage/articleCreator/active', 'ArticleCreatorController@active');

Route::get('/manage/providerArticle', 'ArticleProviderController@show');
Route::post('/manage/providerArticle', 'ArticleProviderController@save');
Route::post('/manage/providerArticle/getGroup', 'ArticleProviderController@getGroup');
Route::post('/manage/providerArticle/getCategories', 'ArticleProviderController@getCategories');
Route::post('/manage/providerArticle/getArticle', 'ArticleProviderController@getArticle');
Route::post('/manage/providerArticle/active', 'ArticleProviderController@active');
Route::post('/manage/providerArticle/startUpdate', 'ArticleProviderController@startUpdate');
Route::post('/manage/providerArticle/Update', 'ArticleProviderController@update');
Route::post('/manage/providerArticle/search', 'ArticleProviderController@search');

// خدمات
//Route::get('/manage/service', 'ServiceController@show');
//Route::post('/manage/service', 'ServiceController@save');
//Route::post('/manage/service/search', 'ServiceController@search');
//Route::post('/manage/service/startUpdate', 'ServiceController@startUpdate');
//Route::post('/manage/service/Update', 'ServiceController@update');
//Route::post('/manage/service/delete', 'ServiceController@delete');

Route::get('/manage/serviceBrand', 'ServiceBrandController@show');
Route::post('/manage/serviceBrand', 'ServiceBrandController@save');
Route::post('/manage/serviceBrand/search', 'ServiceBrandController@search');
Route::post('/manage/serviceBrand/startUpdate', 'ServiceBrandController@startUpdate');
Route::post('/manage/serviceBrand/Update', 'ServiceBrandController@update');
Route::post('/manage/serviceBrand/delete', 'ServiceBrandController@delete');

Route::get('/manage/serviceArea', 'ServiceAreaController@show');
Route::post('/manage/serviceArea', 'ServiceAreaController@save');
Route::post('/manage/serviceArea/search', 'ServiceAreaController@search');
Route::post('/manage/serviceArea/startUpdate', 'ServiceAreaController@startUpdate');
Route::post('/manage/serviceArea/Update', 'ServiceAreaController@update');
Route::post('/manage/serviceArea/delete', 'ServiceAreaController@delete');
Route::post('/manage/serviceArea/active', 'ServiceAreaController@active');

Route::get('/manage/serviceGroup', 'ServiceGroupController@show');
Route::post('/manage/serviceGroup', 'ServiceGroupController@save');
Route::post('/manage/serviceGroup/search', 'ServiceGroupController@search');
Route::post('/manage/serviceGroup/startUpdate', 'ServiceGroupController@startUpdate');
Route::post('/manage/serviceGroup/Update', 'ServiceGroupController@update');
Route::post('/manage/serviceGroup/delete', 'ServiceGroupController@delete');

Route::get('/manage/serviceCategory', 'ServiceCategoryController@show');
Route::post('/manage/serviceCategory', 'ServiceCategoryController@save');
Route::post('/manage/serviceCategory/getGroup', 'ServiceCategoryController@getGroup');
Route::post('/manage/serviceCategory/search', 'ServiceCategoryController@search');
Route::post('/manage/serviceCategory/startUpdate', 'ServiceCategoryController@startUpdate');
Route::post('/manage/serviceCategory/Update', 'ServiceCategoryController@update');
Route::post('/manage/serviceCategory/delete', 'ServiceCategoryController@delete');

Route::get('/manage/createService', 'ServiceCreatorController@show');
Route::post('/manage/createService', 'ServiceCreatorController@save');
Route::post('/manage/serviceCreator/getGroup', 'ServiceCreatorController@getGroup');
Route::post('/manage/serviceCreator/getCategories', 'ServiceCreatorController@getCategories');
Route::post('/manage/serviceCreator/search', 'ServiceCreatorController@search');
Route::post('/manage/serviceCreator/startUpdate', 'ServiceCreatorController@startUpdate');
Route::post('/manage/serviceCreator/Update', 'ServiceCreatorController@update');
Route::post('/manage/serviceCreator/delete', 'ServiceCreatorController@delete');
Route::post('/manage/serviceCreator/active', 'ServiceCreatorController@active');

Route::get('/manage/providerService', 'ServiceProviderController@show');
Route::post('/manage/providerService', 'ServiceProviderController@save');
Route::post('/manage/providerService/getGroup', 'ServiceProviderController@getGroup');
Route::post('/manage/providerService/getCategories', 'ServiceProviderController@getCategories');
Route::post('/manage/providerService/getService', 'ServiceProviderController@getService');
Route::post('/manage/providerService/active', 'ServiceProviderController@active');
Route::post('/manage/providerService/startUpdate', 'ServiceProviderController@startUpdate');
Route::post('/manage/providerService/Update', 'ServiceProviderController@update');
Route::post('/manage/providerService/search', 'ServiceProviderController@search');

Route::get('/manage/aboutUs', 'AboutUsController@show');
Route::post('/manage/aboutUs', 'AboutUsController@save');
Route::get('/manage/numberAnswer', 'NumberAnswerController@show');
Route::post('/manage/numberAnswer', 'NumberAnswerController@save');
Route::get('/manage/timeAnswer', 'TimeAnswerController@show');
Route::post('/manage/timeAnswer', 'TimeAnswerController@save');

//جداول پایه
Route::get('/manage/state', 'StateController@show');
Route::post('/manage/state', 'StateController@save');
Route::post('/manage/state/searchState', 'StateController@search');
Route::post('/manage/state/startUpdateState', 'StateController@startUpdate');
Route::post('/manage/state/UpdateState', 'StateController@update');
Route::post('/manage/state/delete', 'StateController@delete');

Route::get('/manage/city', 'CityController@show');
Route::post('/manage/city', 'CityController@save');
Route::post('/manage/city/searchCity', 'CityController@search');
Route::post('/manage/city/startUpdateCity', 'CityController@startUpdate');
Route::post('/manage/city/UpdateCity', 'CityController@update');
Route::post('/manage/city/delete', 'CityController@delete');

Route::get('/manage/district', 'DistrictController@show');
Route::post('/manage/district', 'DistrictController@save');
Route::post('/manage/district/getCity', 'DistrictController@getCity');
Route::post('/manage/district/searchDistrict', 'DistrictController@search');
Route::post('/manage/district/startUpdateDistrict', 'DistrictController@startUpdate');
Route::post('/manage/district/UpdateDistrict', 'DistrictController@update');
Route::post('/manage/district/delete', 'DistrictController@delete');

//مدیریت
Route::get('/manage/users', 'UserController@show');
Route::post('/manage/users', 'UserController@save');
Route::post('/manage/users/searchUser', 'UserController@search');
Route::post('/manage/users/startUpdateUser', 'UserController@startUpdate');
Route::post('/manage/users/UpdateUser', 'UserController@update');
Route::post('/manage/users/active', 'UserController@active');
Route::POST('/manage/users/changePass', 'UserController@changePass');

//فرم
Route::get('/manage/forms', 'FromController@show');
Route::post('/manage/forms/search', 'FromController@search');
Route::post('/manage/forms/startUpdate', 'FromController@startUpdate');
Route::post('/manage/forms/Update', 'FromController@update');

Route::get('/manage/questionReport', 'QuestionReportController@show');
Route::post('/manage/questionReport', 'QuestionReportController@save');
Route::post('/manage/questionReport/search', 'QuestionReportController@search');
Route::post('/manage/questionReport/startUpdate', 'QuestionReportController@startUpdate');
Route::post('/manage/questionReport/Update', 'QuestionReportController@update');

Route::get('/manage/report', 'ReportController@show');
//سطح دسترسی
Route::get('/manage/accessLevel', 'AccessLevelController@show');
Route::post('/manage/accessLevel', 'AccessLevelController@save');
Route::post('/manage/accessLevel/startUpdate', 'AccessLevelController@startUpdate');
Route::get('/manage/accessLevel/startUpdate', 'AccessLevelController@startUpdate');
Route::post('/manage/accessLevel/Update', 'AccessLevelController@update');

//مناطق تحت پوشش
Route::get('/manage/covered', 'CoveredController@show');
Route::post('/manage/covered/showCity', 'CoveredController@showCity');
Route::post('/manage/covered/save', 'CoveredController@save');
Route::post('/manage/covered/showSelectedCity', 'CoveredController@showSelectedCity');

//تراکنش ها
Route::get('/manage/transAction', 'TransActionController@show');

//تبلیغات و برندينگ
Route::get('/manage/upLogo', 'UpLogoController@show');
Route::post('/manage/upLogo', 'UpLogoController@save');
Route::get('/manage/upLogoSpecial', 'UpLogoSpetialController@show');

//مدیریت تماس ها
Route::get('/manage/freeCall', 'FreeCallController@show');
Route::post('/manage/freeCall/save', 'FreeCallController@save');
Route::post('/manage/freeCall/changeActivity', 'FreeCallController@changeActivity');
Route::get('/manage/callList', 'CallListController@show');
Route::get('/manage/freeCallForCustomer', 'FreeCallForCustomerController@show');
Route::post('/manage/freeCallForCustomer', 'FreeCallForCustomerController@save');
Route::post('/manage/freeCallForCustomer/delete', 'FreeCallForCustomerController@delete');
Route::post('/manage/freeCallForCustomer/active', 'FreeCallForCustomerController@active');

//ليست خدمات و محصولات
Route::get('/manage/freeCall', 'FreeCallController@show');
Route::get('/manage/callList', 'CallListController@show');

//رویداد نگاری
Route::get('/manage/logHistory', 'LogHistoryController@show');
Route::post('/manage/logHistory/search', 'LogHistoryController@search');

//لیست خدمات و محصولات
Route::get('/manage/newProduct', 'NewProductController@show');
Route::get('/manage/basketList', 'BasketListController@show');
Route::post('/manage/basketList', 'BasketListController@save');
Route::post('/manage/basketList/deleteArticle', 'BasketListController@deleteArticle');
Route::post('/manage/basketList/deleteService', 'BasketListController@deleteService');
Route::post('/manage/basketList/updateArticle', 'BasketListController@updateArticle');
Route::post('/manage/basketList/updateService', 'BasketListController@updateService');
Route::get('/manage/selectProduct', 'SelectProductController@show');
Route::post('/manage/selectProduct', 'SelectProductController@search');
Route::post('/manage/selectProduct/searchProduct', 'SelectProductController@searchProduct');
Route::post('/manage/selectProduct/saveArticle', 'SelectProductController@saveArticle');
Route::post('/manage/selectProduct/saveService', 'SelectProductController@saveService');

//شماره های تجاری
Route::get('/manage/businessNumber', 'BusinessNumberController@show');
Route::post('/manage/businessNumber/search', 'BusinessNumberController@search');

// پروفایل
Route::get('/manage/profile', 'ProfileController@show');
Route::post('/manage/profile', 'ProfileController@save');
Route::POST('/manage/profile/checkPassword', 'ProfileController@checkPassword');
Route::POST('/manage/profile/changePass', 'ProfileController@changePass');
Route::POST('/manage/profile/changeTheme', 'ProfileController@changeTheme');

Route::get('/manage/createReseller', 'CreateResellerController@show');
Route::post('/manage/createReseller', 'CreateResellerController@save');
Route::post('/manage/createReseller/productServicePage', 'CreateResellerController@productServicePage');
Route::post('/manage/createReseller/searchUser', 'CreateResellerController@search');
Route::post('/manage/createReseller/startUpdateUser', 'CreateResellerController@startUpdate');
Route::post('/manage/createReseller/UpdateUser', 'CreateResellerController@update');
Route::post('/manage/createReseller/active', 'CreateResellerController@active');
Route::POST('/manage/createReseller/changePass', 'CreateResellerController@changePass');
Route::POST('/manage/createReseller/saveArticle', 'CreateResellerController@saveArticle');
Route::POST('/manage/createReseller/saveService', 'CreateResellerController@saveService');
Route::post('/manage/createReseller/takhsis', 'CreateResellerController@takhsis');
Route::post('/manage/createReseller/productService', 'CreateResellerController@productService');
Route::post('/manage/createReseller/deleteArticle', 'CreateResellerController@deleteArticle');
Route::post('/manage/createReseller/deleteService', 'CreateResellerController@deleteService');
Route::post('/manage/createReseller/covered', 'CreateResellerController@covered');
Route::post('/manage/createReseller/saveCovered', 'CreateResellerController@saveCovered');

Route::get('/manage/contact', 'ContactController@show');
Route::get('/manage/appUser', 'AppUserController@show');
