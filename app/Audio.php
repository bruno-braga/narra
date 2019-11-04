<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = 'audios';
    protected $fillable = ['path', 'filename'];

    public function episodes()
    {
        return $this->morphTo();
    }

    public function getAudioPathAttribute()
    {
        return $this->path . '' . $this->filename;
    }
}
