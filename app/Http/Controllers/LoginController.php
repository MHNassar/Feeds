<?php

namespace App\Http\Controllers;

use App\Worker;
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
            $user = Worker::where('work_email', $login)->first();
            return $user;
        }

        return response()->json(['data' => '1', 'message' => 'Invalid Credentials '], 401);

    }
}
