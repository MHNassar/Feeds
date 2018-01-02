<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//use Illuminate\Support\Facades\DB;


$router->get('/', function () use ($router) {
    $users = DB::table('ir_attachment')->limit('1')->get();
    return $users;

});

$router->post('login', 'LoginController@login');
$router->get('tasks/{id}', 'TasksController@getTask');
$router->get('tasks/stage/{task_id}/{state}', 'TasksController@changeState');
$router->post('task/summary', 'TasksController@setOrderSummary');
$router->post('user/location', 'TasksController@setLocation');
