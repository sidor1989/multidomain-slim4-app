<?php

namespace App\Models;

class Domain extends BaseModel{

    protected $fillable = ['domains','name_domain','album_id','name_town_cyrilic','name_diler','email','site','address','phones','towns','map_coordinats','active','content_1','content_2','content_3'];

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function courses()
    {
        return $this->hasMany('App\Models\Course');
    }

    public function album()
    {
        return $this->belongsTo('App\Models\Album');
    }


}
