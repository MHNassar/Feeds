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

            //track .....
            // Get partner_id
            $partner_id = DB::table('res_users')->where('login', $task->cl_id)->first()->partner_id;
            $mailId = DB::table('mail_message')->insert(['create_date' => Carbon::now(), 'write_date' => Carbon::now(), 'write_id' => 1
                , 'create_id' => 1, 'subtype_id' => 18, 'res_id' => $task_id, 'author_id' => $partner_id, 'model' => 'project.task', 'no_auto_thread' => 0
                , 'date' => Carbon::now(), 'message_type' => 'notification', 'website_published' => 1
            ]);

            $id = DB::table('mail_tracking_value')->max('id');
            $mailId = DB::table('mail_message')->max('id');

            $lastTrack = DB::table('mail_tracking_value')->where('mail_message_id', $mailId + 1)->orderBy('id', 'desc')->first();
            $user_id = $lastTrack->create_uid;
            $old_value_char = $lastTrack->new_value_char;
            $old_value_int = $lastTrack->new_value_integer;
            DB::table('mail_tracking_value')->insert([
                "id" => $id + 1,
                "mail_message_id" => $mailId,
                'create_uid' => $user_id, 'field_type' => 'many2one',
                'create_date' => Carbon::now(), 'field' => 'stage_id', 'field_desc' => 'Stage',
                'new_value_char' => $state, 'write_date' => Carbon::now(), 'new_value_integer' => $stateId,
                'old_value_char' => $old_value_char, 'old_value_integer' => $old_value_int
            ]);


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

    public function setLocation(Request $request)
    {
        $user_id = $request->get('user_id');
        $long = $request->get('long');
        $lat = $request->get('lat');
        $id = DB::table('x_workers_location')->max('id');
        $att = [
            'id' => $id + 1,
            'x_worker_id' => $user_id,
            'x_worker_longitude' => $long,
            'x_worker_latitude' => $lat,
            'create_date' => Carbon::now()];

        DB::table('x_workers_location')->insert([$att]);

        return response()->json(['message' => 'Succsess'], 200);


    }
}
