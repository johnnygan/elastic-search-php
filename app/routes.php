<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', function()
{
	return View::make('hello');
});

/* about page
*/
Route::get('about', 'HomeController@showWelcome');

/* contact us page
*/
Route::get('contact', 'HomeController@contact');

/* cinsay_db::user table
*/
Route::resource('users', 'UserController');

/* Elastic Search
*/
Route::resource('elastics', 'ESController');

/* Elastic Search Cluster
*/
Route::resource('cluster', 'ClusterController');
/*Route::get("cluster/index", [
	"as" => "cluster.index", //route name, used in link_to_route call
	"uses" => "clusterController@indexAction"
]);*/

/* Elastic Search Node
*/
Route::resource('node', 'NodeController');

Route::get("nodes/threads", [
	"as" => "nodes.threads", //route name, used in link_to_route call
	"uses" => "NodeController@threads"
]);

/* Elastic Indices
*/
Route::resource('indices', 'IndicesController');

/* Elastic Search Node
*/
Route::resource('search', 'SearchController');