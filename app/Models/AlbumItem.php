<?php

namespace App\Models;

class AlbumItem extends BaseModel{

    const UPLOAD_DIR = __DIR__ . '/../public_html/uploads/';

    protected $fillable = ['album_id', 'name','filename','content'];

    public function album()
    {
        return $this->belongsTo('App\Models\Album');
    }

    public function removeImage($directory)
    {
        if($this->filename != null)
        {
            $filename = $directory.$this->filename;
            // var_dump(file_exists($filename));die();
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
    }



}
