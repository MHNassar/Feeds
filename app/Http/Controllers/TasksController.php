<?php

namespace App\Http\Controllers;

use App\Details;
use App\State;
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

    public function changeState($state, $task_id)
    {
        $stateId = $this->getStateId($state);
        if ($stateId == -1) {
            return response()->json(['message' => 'Bad Request - State Not Found'], 400);
        }
        $task = Details::find($task_id);
        if ($task) {
            $task->stage_id = $stateId;
            $task->save();
            return response()->json(['message' => 'Succsess'], 200);
        }
        return response()->json(['message' => 'Bad Request - Task Not Found'], 400);
    }

    private function getStateId($state)
    {
        $stage = State::where("name", $state)->first();
        if ($stage) {
            return $stage->id;
        }
        return "-1";
    }
}
