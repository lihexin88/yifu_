<?php

namespace app\index\model;

use think\Model;

class UserConfig extends Model
{
    protected $table = 'sn_user_config';

    public function user()
    {
        return $this->belongsTo('User', 'id', 'uid');
    }
}