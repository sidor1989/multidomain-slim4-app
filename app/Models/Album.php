<?php

namespace App\Models;

class Album extends BaseModel{


    protected $fillable = ['name','description','active'];

    public function domain()
    {
        return $this->hasOne('App\Models\Domain');
    }

    public function albumItems()
    {
        return $this->hasMany('App\Models\AlbumItem');
    }


}
