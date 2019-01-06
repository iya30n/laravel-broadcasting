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

Route::view('/' , 'welcome');



Route::middleware('auth')->name('chat.')->prefix('chat')->group(function(){
	$this->get('users' , 'ChatsController@index')->name('users.all');
	$this->get('/{user}' , 'ChatsController@chat')->name('user');
	$this->post('send' , 'ChatsController@send');
});

//Broadcast::routes();
