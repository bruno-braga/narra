<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Program;

class Category extends Model
{

    protected $fillable = ['category_id'];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'category_program');
    }
}
