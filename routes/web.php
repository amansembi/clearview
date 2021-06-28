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
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
/******************************Admin view Routes**********************************************/
Route::group(['as'=> 'admin','middleware'=>['auth'=>'admin']],function(){
	Route::get('admin', 'admin\AdminController@index')->name('admin');
		});
Route::get('/admin/users', 'admin\AdminController@allUsers')->name('users');
Route::post('admin/deleteuser', 'admin\AdminController@deleteuser')->name('deleteuser');
Route::get('admin/editusers/{id}', 'admin\AdminController@editusers')->name('editusers');
Route::post('admin/updateuser', 'admin\AdminController@updateuser')->name('updateuser');
Route::get('/admin/requests', 'admin\AdminController@requests')->name('requests');
Route::get('/admin/request/{id}', 'admin\AdminController@viewRequest')->name('view');
Route::get('/terms-conditions', 'pageController@termsConditions')->name('termsConditions');
Route::get('/privacy-policy', 'pageController@privacyPolicy')->name('privacyPolicy');
Route::get('/admin/news/', 'admin\newsController@news')->name('news');
Route::post('/admin/news/addnews/', 'admin\newsController@addnews')->name('addnews');


/******************************Front end view Routes**********************************************/
//Reset password API
Route::get('/changesuccessfullypass/{id}', 'registerController@changesuccessfullypass')->name('changesuccessfullypass');
Route::get('/passexpired/{id}', 'registerController@passexpired')->name('passexpired');
Route::get('/reset_password/{token}', 'registerController@resetPassword')->name('reset_password');
Route::get('/confirm_user/{token}', 'registerController@confirm_user');
Route::get('/confirm_technician/{token}', 'technicianController@confirm_technician');
Route::get('/technician_reset_password/{token}', 'technicianController@resetPassword');
//Change password API
Route::post('/reset_password/changepassword', 'registerController@changepassword')->name('changepassword');
Route::post('/technician_reset_password/technicianchangepassword', 'technicianController@changepassword')->name('technicianchangepassword');

/******************************APIs Routes**********************************************/

//Register API
Route::post('/api/userRegister', 'registerController@register');
//Register final API
Route::post('/api/registerFinal', 'registerController@registerfinal');
//Login API
Route::post('/api/login', 'registerController@login');
//Forgot password API
Route::post('/api/forgotpassword', 'registerController@forgotpassword');
//Service Request API
Route::post('/api/serviceRequest', 'serviceRequestController@serviceRequest');
Route::post('/api/tonerorder', 'serviceRequestController@tonerorder');
Route::post('/api/enterameter', 'serviceRequestController@enterameter');
Route::post('/api/contactus', 'contactusController@contactus');
Route::post('/api/technicianregister', 'technicianController@register');
Route::post('/api/technicianlogin', 'technicianController@login');
Route::post('/api/technicianforgotpassword', 'technicianController@forgotpassword');
Route::get('/api/allrequests', 'serviceRequestController@allrequests');
Route::post('/api/allorders', 'serviceRequestController@allorders');

Route::post('/api/accept_request', 'serviceRequestController@accept_request');
Route::post('/api/customer_detail', 'serviceRequestController@customer_detail');
Route::post('/api/work_complete', 'serviceRequestController@work_complete');
Route::post('/api/userlatlng', 'latlngController@userlatlngs');
Route::post('/api/technicianlatlng', 'latlngController@technicianlatlng');
Route::post('/api/userchat', 'chatController@enterchat');
Route::post('/api/chatList', 'chatController@chatList');
Route::post('/api/technicianOnline', 'technicianController@technicianOnline');
Route::post('/api/technicianOffline', 'technicianController@technicianOffline');
Route::post('/api/technicianOnSite', 'technicianController@technicianOnSite');
Route::post('/api/technicianEnRoute', 'technicianController@technicianEnRoute');
Route::post('/api/technicianById', 'technicianController@technicianById');
Route::post('/api/completedRequests', 'technicianController@completedRequests');
Route::post('/api/workStartTime', 'technicianController@workStartTime');
Route::post('/api/totalTravelTime', 'technicianController@totalTravelTime');
Route::post('/api/totalWorkTime', 'technicianController@totalWorkTime');
Route::post('/api/distanceByLatLong', 'technicianController@distanceByLatLong');
Route::post('/api/reviews', 'technicianController@reviews');
Route::post('/api/reachedTime', 'technicianController@reachedTime');




Route::post('/api/news', 'newsController@news');
Route::get('/api/allnews', 'newsController@allnews');

//Admin 



