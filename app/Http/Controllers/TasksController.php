<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function getTask($user_id)
    {
        $day = date("Y-m-d");
        $tasks = Task::where('x_name', $user_id)
            ->where('x_active_worker', true)
            ->where('date', $day)
            ->get();
        return $tasks;

    }
}
