<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SocialLoginController;




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


/* ------ original ----
Route::get('/', function () {
    return view('welcome');
});


*/

Auth::routes();



Route::get('social-auth/{provider}/callback',[SocialLoginController::class,'providerCallback']);
Route::get('social-auth/{provider}',[SocialLoginController::class,'redirectToProvider'])->name('social.redirect');



Route::get('/', function (){
    return view('welcome');
});





/*
Route::get('/order/create/{robject_id}', [App\Http\Controllers\OrderControllerOLD::class, 'create']);
Route::post('/order/store', [App\Http\Controllers\OrderControllerOLD::class, 'store']);
*/


Route::group(['middleware' => 'auth'], function (){

    Route::group(['prefix'=>'account'], function(){

        ##Route::view('/', 'account.index')->name('home');
        Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('home');

        Route::get('{any?}', [App\Http\Controllers\AccountController::class, 'index'])->where('any', '^((?!settings|api).)*$');  //->where('any', '^(?!settings)*$');   // ->where('any', '^((?!about|contact-us).)*$');  //->where('any','.*');



        Route::group(['prefix'=>'api'], function(){

            /*Route::group(['prefix'=>'dashboard'], function(){
                Route::get('/index', [App\Http\Controllers\AccountController::class, 'ajax_index'])->name('dashboard/index');
            });*/


            Route::group(['prefix'=>'order'], function(){

                Route::get('/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order/create');
                Route::get('/get_ordertype_detail/{order_type}', [App\Http\Controllers\OrderController::class, 'get_ordertype_detail'])->name('order/get_ordertype_detail');
                Route::post('/store', [App\Http\Controllers\OrderController::class, 'store'])->name('order/store');

                Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('order/index');

                Route::post('{order_id}/send_request_again', [App\Http\Controllers\OrderController::class, 'send_request_again'])->name('order/send_request_again');

            });



            Route::group(['prefix'=>'user', 'middleware' => 'role:admin'], function() {
                Route::get('/index', [App\Http\Controllers\UserController::class, 'index'])->name('user/index');

                /*Route::get('/create', [App\Http\Controllers\TariffController::class, 'create'])->name('tariff/create');
                Route::get('/edit/{id}', [App\Http\Controllers\TariffController::class, 'edit'])->name('tariff/edit');

                Route::post('/store', [App\Http\Controllers\TariffController::class, 'store'])->name('tariff/store');
                Route::post('/update/{id}', [App\Http\Controllers\TariffController::class, 'update'])->name('tariff/update');
                */

                Route::post('/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user/update');
                Route::get('/loginas/{id}', [App\Http\Controllers\UserController::class, 'login_as_user'])->name('user/loginas');

            });


            Route::get('/getcall', [App\Http\Controllers\OrderController::class, 'getcall'])->name('order/getcall');
            Route::get('/getclient', [App\Http\Controllers\OrderController::class, 'getclient'])->name('order/getclient');

        });


        Route::get('/settings', [App\Http\Controllers\AccountController::class, 'settings'])->name('account/settings');
        Route::post('/settings/update', [App\Http\Controllers\AccountController::class, 'settings_update'])->name('account/settings/update');



    });

});






