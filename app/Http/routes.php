<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('/update-tables', 'ZohoAPIController@updateTables');

Route::auth();
Route::get('auth/logout','Auth\AuthController@getLogout');

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'zohoAuth'], function () {
		Route::get('/work-done', 'TimeEntryController@showWorkDonePage');
		Route::post('/work-done/add', 'TimeEntryController@saveTimeEntry');

		Route::post('/work-done/delete_entry', 'TimeEntryController@deleteTimeEntry');

		Route::post('/work-done/edit_entry', 'TimeEntryController@editTimeEntry');

		Route::get('/projects', 'ProjectController@showProjectsPage');

		Route::post('/projects', 'ProjectController@projectEnabledDisabled');

		Route::post('/work-done', 'TimeEntryController@sync');
	});

	Route::post('/authtoken', 'AuthTokenController@saveAuthToken');

	Route::get('/authtoken', 'AuthTokenController@showAuthTokenPage');

	Route::get('/settings', 'SettingsController@showSettingsPage');
	Route::post('/settings', 'SettingsController@resetPassword');
});






















// /*For debugging*/
// if(App::environment('local')) {

//     Route::get('/drop', function() {

//         $db = Config::get('database.connections.mysql.database');

//         DB::statement('DROP database '.$db);
//         DB::statement('CREATE database '.$db);

//         return 'Dropped '.$db.'; created '.$db.'.';
//     });

// };
// /*End "for debugging"*/