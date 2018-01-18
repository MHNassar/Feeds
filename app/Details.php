<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Details extends Model
{
    protected $table = 'project_task';
    public $timestamps = false;
    protected $appends = ['cl_id'];

    public function client()
    {
        return $this->hasOne(Clients::class, 'id', 'partner_id');
    }

    public function getClIdAttribute()
    {
        return Task::where('task_id', $this->id)->first()->x_name;

    }


}
