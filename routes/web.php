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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
	// Route::get('/home', function () { 
	//     return view('dashboard');
	// });

	Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

	Route::resource('zone', 'ZoneController');
	Route::post('zone/update', 'ZoneController@update')->name('zone.update');
	Route::get('zone/destroy/{id}', 'ZoneController@destroy');

	Route::resource('office', 'OfficeController');
	Route::post('office/update', 'OfficeController@update')->name('office.update');
	Route::get('office/destroy/{id}', 'OfficeController@destroy');
	
	Route::resource('designation', 'DesignationController');
	Route::post('designation/update', 'DesignationController@update')->name('designation.update');
	Route::get('designation/destroy/{id}', 'DesignationController@destroy');

	Route::resource('signatories', 'SignatoryController');
	Route::post('signatories/update', 'SignatoryController@update')->name('signatories.update');
	Route::get('signatories/destroy/{id}', 'SignatoryController@destroy');

	Route::resource('pr', 'PRequestController');
	Route::post('pr/update', 'PRequestController@update')->name('pr.update');
	Route::get('pr/destroy/{id}', 'PRequestController@destroy');

	Route::resource('po', 'POrderController');
	Route::post('po/update', 'POrderController@update')->name('po.update');
	Route::get('po/destroy/{id}', 'POrderController@destroy');

	Route::resource('voucher', 'VoucherController');
	Route::post('voucher/update', 'VoucherController@update')->name('voucher.update');
	Route::get('voucher/destroy/{id}', 'VoucherController@destroy');

	Route::resource('to', 'TOrderController');
	Route::post('to/update', 'TOrderController@update')->name('to.update');
	Route::get('to/destroy/{id}', 'TOrderController@destroy');

	Route::resource('canvass', 'CanvassController');
	Route::post('canvass/update', 'CanvassController@update')->name('canvass.update');
	Route::get('canvass/destroy/{id}', 'CanvassController@destroy');

	Route::resource('project', 'ProjectController');
	Route::post('project/update', 'ProjectController@update')->name('project.update');
	Route::get('project/destroy/{id}', 'ProjectController@destroy');

	Route::get('charts', 'ChartController@index')->name('charts');

	//examples only
	Route::get('typography', 'SampleController@index')->name('typography');

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('map', function () {
		return view('pages.map');
	})->name('map');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

	Route::get('rtl-support', function () {
		return view('pages.language');
	})->name('language');

	Route::get('upgrade', function () {
		return view('pages.upgrade');
	})->name('upgrade');
	//end of examples only
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
});
Route::group(['middleware' => 'auth'], function () {
	Route::get('table-list', 'DynamicPDFController@index')->name('table');	
	Route::get('table-list/pdf', 'DynamicPDFController@pdf')->name('table.pdf');
});

