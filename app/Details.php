<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Details extends Model
{
    protected $table = 'project_task';

    public function client()
    {
        return $this->hasOne(Clients::class, 'id', 'partner_id');
    }

}
