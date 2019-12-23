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
    protected $fillable = [
        'language_id',
        'copyright',
        'explicit',
        'subtitle',
        'author',
        'owner_name'
    ];

    protected $appends = ['explicit_string'];

    /**
     * A Setting has belongs to a Program
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function setting()
    {
        return $this->belongsTo(Program::class);
    }

		/**
		 * Get the route key for the model.
		 *
		 * @return string
		 */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getExplicitStringAttribute()
    {
        return $this->explicit ? 'Yes' : 'No';
    }

}
