<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Works extends Model
{
    protected $guarded = array('id');

    // 以下を追記
    public static $rules = array(
        'name' => 'required',
    );

    public function histories()
    {
      return $this->hasMany('App\History');

    }
}
