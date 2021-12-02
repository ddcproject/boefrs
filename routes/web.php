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

/* Home */
Route::get('/', 'InitialController@index');
Route::get('/init', 'InitialController@index')->name('init');
Route::get('/home', 'HomeController@index')->name('home');

/* Auth */
Route::group(['middleware' => ['auth']], function() {
	Route::resource('roles', 'RoleController');
	Route::resource('users', 'UserController');
	Route::resource('dashboard', 'DashboardController');
	Route::resource('code', 'CodeController');
	Route::resource('list-data', 'ListDataController');
	Route::resource('lab', 'LabController');
});

/* dashboard */
Route::get('/dashboard/prov/{id}', 'DashboardController@provinceDashboard')->name('prov');
Route::post('/dashboard', 'DashboardController@index_post')->name('dashboard.post');

/* log */
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

/* Register */
Route::get('/register', '\App\Http\Controllers\Auth\RegisterController@index')->name('register');
Route::post('register', '\App\Http\Controllers\Auth\RegisterController@register')->name('register');
Route::get('/getHospByProv', '\App\Http\Controllers\Auth\RegisterController@getHospByProv')->name('getHospByProv');

/* Logout */
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['auth']], function() {
	/* CodeGen :: Ajax request for generate lab code */
	Route::post('ajaxRequest', 'CodeController@ajaxRequestPost')->name('ajaxRequest');
	Route::get('/ajaxRequestTable', 'CodeController@ajaxRequestTable')->name('ajaxRequestTable');

	/* Ajax select */
	Route::post('ajaxSelect', 'CodeController@ajaxRequestSelect')->name('ajaxSelect');

	/* list data */
	Route::get('list', 'ListDataController@listToDatatable')->name('list');
	//Route::post('data/list', 'ListDataController@listData')->name('list-data');
	Route::post('data/search', 'ListDataController@ajaxListData')->name('ajaxSearchData');
	Route::post('data/patients', 'ListDataController@ajaxListDataAfterDeleted')->name('ajaxListDataAfterDel');

	/* patient */
	Route::get('/patient/create/{id}', 'PatientsController@create')->name('createPatient');
	Route::post('patient/add', 'PatientsController@addPatient')->name('addPatient');
	Route::get('patient/edit/{id}', 'PatientsController@editPatient')->name('editPatient');
	Route::get('/patient/show/{id}', 'PatientsController@show')->name('viewPatient');
});

/* Ajax request hosp */
Route::get('/ajaxGetHospByProv', 'UserController@ajaxGetHospByProv')->name('ajaxGetHospByProv');

/* Ajax lab */
Route::post('ajaxAddLab', 'LabController@ajaxAddLab')->name('ajaxAddLab');

/* Captcha */
Route::get('/refreshcaptcha', 'CaptchaController@refreshCaptcha');

/* Sample Submission Form */
Route::get('/sample-submissions-form', array(
	'as' => 'sample-submission.form',
	'uses' => 'SampleSubmissionsController@Form_Sample_Submissions'
));

/* delete */
Route::post('/code/delete', 'CodeController@confirmDestroy')->name('codeSoftConfirmDelete');
Route::get('/code/delete/{id}','CodeController@destroy')->name('codeSoftDelete');

/* fetch district, fetch sub-district */
Route::post('province/district', 'PatientsController@districtFetch')->name('districtFetch');
Route::post('province/district/sub-district', 'PatientsController@subDistrictFetch')->name('subDistrictFetch');

/* lab data */
Route::get('lab', 'LabController@listToDatatable')->name('lab');
Route::get('/lab/create/{id}', 'LabController@create')->name('createLab');
Route::get('/lab/show/{id}', 'LabController@show')->name('viewLab');
Route::get('/lab/edit/{id}', 'LabController@edit')->name('editLab');
Route::post('lab-store', 'LabController@store')->name('lab-store');

/* PDF Generate */
Route::get('/lab/show-pre-print/{id}', 'LabController@show_preprint')->name('previewprintLab');
Route::get('/report/labresult/{id}', 'LabPDFController@LabresultPDF')->name('viewprintpdfforlab');

/* Counter */
Route::get('/counter', 'CounterController@index')->name('counter');

/* map */
Route::get('/map/spread', 'MapController@index')->name('spread');
Route::get('/map/pin', 'MapController@pin')->name('pin');
Route::get('/map/marker', 'MapController@marker')->name('marker');
Route::get('/map/chart', 'MapController@chart')->name('chart');

/* export */
Route::get('/export/csv', 'ExportsController@index')->name('export-csv');
Route::post('export', 'ExportsController@exportFastExcel')->name('export.search');

Route::get('/getFile/{file}', 'ExportsController@downloadFile');
