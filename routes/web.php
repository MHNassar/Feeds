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


$router->get('test', function () use ($router) {

    $tasks = \App\Details::where('id', 27461)->with('client')->first();
    return $tasks->client;

});
$router->get('/', function () use ($router) {
    $users = \App\State::all();
    return $users;

});

$router->post('login', 'LoginController@login');
$router->get('tasks/{id}', 'TasksController@getTask');
$router->get('tasks/stage/{task_id}/{state}', 'TasksController@changeState');
