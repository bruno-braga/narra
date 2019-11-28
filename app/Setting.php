<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Program;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'copyright', 'subtitle', 'author', 'owner_name'];

    /**
     * A Setting has belongs to a Program
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function setting()
    {
        return $this->belongsTo(Program::class);
    }
}
