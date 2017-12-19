<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Task extends Model
{
    protected $table = 'account_analytic_line';
//
    protected $appends = ['task_name', 'task_time', 'client_name', 'client_address', 'client_phone'];



    protected $hidden = ["id", "create_uid", "user_id", "account_id", "company_id", "write_uid", "amount", "unit_amount",
        "date", "create_date", "write_date", "partner_id", "name", "code", "currency_id", "ref", "general_account_id",
        "move_id", "product_id", "product_uom_id", "amount_currency", "so_line", "is_timesheet", "sheet_id", "x_name",
        "x_Status", "x_name_bs", "x_account_id", "issue_id", "date_time", "x_sstage", "x_stage_name", "x_active_worker"];

    public function getTaskNameAttribute()
    {
        return Details::where('id', $this->task_id)->first()->name;
    }

    public function getTaskTimeAttribute()
    {
        return Carbon::parse($this->date_time)->format('H:i:s');
    }

    public function getClientNameAttribute()
    {
        return Details::where('id', $this->task_id)->first()->client->name;

    }

    public function getClientPhoneAttribute()
    {
        return Details::where('id', $this->task_id)->first()->client->x_mobile;

    }

    public function getClientAddressAttribute()
    {
        $clients = Details::where('id', $this->task_id)->first();
        $address = $clients->client->street . '  ' . $clients->client->city . '  ' . $clients->client->street2 . '  ' . $clients->client->x_neighborhood . '  ' . $clients->client->x_Building . '  ' . $clients->client->x_Level_No . '  ' . $clients->client->x_Home_No;
        return $address;

    }


}
