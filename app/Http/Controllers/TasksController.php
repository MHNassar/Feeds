<?php

namespace App\Http\Controllers;

use App\Details;
use App\State;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function setOrderSummary(Request $request)
    {
        $total = $request->get('total');
        $note = $request->get('note');
        $attachment = $request->get('attachment');
        $addtion_cost = $request->get('addtion_cost');
        $task_id = $request->get('task_id');

        $task = Details::find($task_id);
        if ($task) {
            $task->x_full_received_money = $total;
            $task->x_note = $note;
            $task->save();
            $sale_line_id = $task->sale_line_id;
            DB::table('sale_order_line')
                ->where('id', $sale_line_id)
                ->update(['price_unit' => $addtion_cost]);
            if ($attachment) {
                $image = base64_decode($attachment);
                $image_name = date("YmdHis") . "_" . mt_rand(100000000, 999999999) . '.png';
                $path = 'public/' . $image_name;
                file_put_contents($path, $image);
                $att = [
                    "create_date" => Carbon::now(),
                    "write_date" => Carbon::now(),
                    "res_model" => "project.task ",
                    "write_uid" => 1,
                    "res_name" => "Public user...",
                    "db_datas" => null,
                    "file_size" => 3193,
                    "create_uid" => $task->x_name,
                    "company_id" => 1,
                    "index_content" => "image",
                    "type" => "binary",
                    "public" => false,
                    "store_fname" => "57/57e92b95099cc0b28160dbab8d9927b6a60a637a",
                    "description" => null,
                    "res_field" => "image",
                    "mimetype" => "image/png",
                    "name" => url($path),
                    "url" => null,
                    "res_id" => $task_id,
                    "checksum" => "57e92b95099cc0b28160dbab8d9927b6a60a637a",
                    "datas_fname" => url($path)
                ];
                DB::table('ir_attachment')->insert([$att]);
            }
            return response()->json(['message' => 'Succsess'], 200);
        }

    }
}
