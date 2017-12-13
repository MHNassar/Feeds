<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $login = $request->get('username');
        $password = $request->get('password');
        $checkLogin = DB::table('res_users')->where('login', $login)->where('password', $password)->count();
        if ($checkLogin > 0) {
            return ['login' => true];
        }
        return ['login' => false];

    }
}
