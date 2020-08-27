<?php

namespace App\Models;

class Course extends BaseModel{

    protected $fillable = ['domain_id','title','lectors', 'city', 'img','date1','date2','tags','size','price','active','data','api_foriegn_key'];

    public function domians()
    {
        return $this->belongsTo('App\Models\Domain');
    }


}
