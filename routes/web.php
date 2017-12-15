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

    $users = DB::table('hr_employee')->get();
    return $users;

});
$router->get('/', function () use ($router) {
    try {
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        echo $e;
    }

});

$router->post('login', 'LoginController@login');
