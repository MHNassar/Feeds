<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Worker extends Model
{
    protected $table = 'hr_employee';

    protected $appends = ['jop'];


    protected $hidden = ['create_date', 'coach_id', 'resource_id', 'color', 'message_last_post',
        'marital', 'identification_id', 'bank_account_id', 'job_id', 'country_id', 'parent_id',
        'department_id', 'create_uid', 'birthday', 'write_date', 'sinid', 'write_uid',
        'work_location', 'ssnid', 'passport_id', 'notes', 'timesheet_cost', 'x_branch_worker', 'address_id', 'address_home_id'];


    public function getJopAttribute()
    {
        return Jop::where('id', $this->job_id)->first()->name;
    }
}
