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




Route::get('/', 'PostController@view');
Route::post('lpu/add/', 'PostController@add');
Route::match(['get','delete'],'lpu/del', 'PostController@del');
Route::match(['put','post'],'lpu/upd', 'PostController@upd');
Route::match(['get','post'],'lpu/filter', 'PostController@filter');



//Route::get('lpu/{id}', 'PostController@find');


//Route::get('lpu/filter/{id?}&{logic?}', 'PostController@filter1');

//Route::get('lpu/tes', 'PostController@tes');


//curl -X POST -d "id=2&name=gffdd" "http://localhost/lpu/add"

//curl   "http://localhost/lpu/del?id=4"
//curl  -X  DELETE "http://localhost/lpu/del/?id=2"

//curl  -X  PUT -d "id=5&name=ert1" "http://localhost/lpu/upd"
//curl -d "id=5&name=ytttt" "http://localhost/lpu/upd"





//curl  -X  PUT -d "name=ert" "http://localhost/lpu/upd/5"
//curl -d "name=ytttt" "http://localhost/lpu/upd/5"



//curl "http://localhost/lpu/filter?id=1
