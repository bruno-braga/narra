<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Setting;

class Language extends Model
{
    /**
     * A Program has one Setting
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(Setting::class);
    }
}
