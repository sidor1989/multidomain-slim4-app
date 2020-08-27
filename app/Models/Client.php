<?php

namespace App\Models;

class Client extends BaseModel{

    const UPLOAD_DIR = __DIR__ . '/../public_html/uploads/';

    protected $fillable = ['domain_id','name','description','sub_name','img','active','content'];
/*
    public function domain()
    {
        return $this->hasOne('App\Domain');
    }
*/
    public function domain()
    {
        return $this->belongsTo('App\Models\Domain');
    }

    public function remove( $directory)
    {
        $this->removeImage($directory);
        $this->delete();
    }
    
    public function removeImage($directory)
    {
        if($this->img != null)
        {
            $filename = $directory.$this->img;
           // var_dump(file_exists($filename));die();
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }

    public function getImage()
    {
        if($this->img == null)
        {
            return '/uploads/no-image.png';
        }
        return '/uploads/'.$this->img;
    }
    
}
