<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['path', 'filename'];
    // protected $appends = ['image_path'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getImagePathAttribute()
    {
        return $this->path . '' . $this->filename;
    }
}
