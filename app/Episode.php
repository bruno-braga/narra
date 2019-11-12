<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Audio;
use App\Image;
use App\Program;

use App\Traits\UploadTrait;

class Episode extends Model
{
    use UploadTrait;

    /**
     * Uploaded audio.
     *
     * @var UploadedFile
     */
    public static $file;

    /**
     * Uploaded image.
     *
     * @var UploadedFile
     */
    public static $cover;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id', 'program_id', 'description'
    ];

    /**
     * Enables us to hook into model event's
     *
     * @return void 
     */
    public static function boot()
    {
        parent::boot();

        static::created(function($episode) {
            $episode->storeOnFileAndDb(self::$cover, 'images', $episode);
            $episode->storeOnFileAndDb(self::$file, 'audios', $episode);
        });

        static::saving(function($episode) {
            $episode->updateFileAndDb(self::$cover, 'images', $episode);
            $episode->updateFileAndDb(self::$file, 'audios', $episode);
        });

        static::deleting(function($episode) {
            $episode->deleteFileAndDb($episode, 'images');
            $episode->deleteFileAndDb($episode, 'audios');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* TO DO: Actually this should be one to one */
    /**
     * An Episode has one or many only one audios 
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function audios()
    {
        return $this->morphMany(Audio::class, 'audiable');
    }

    /**
     * A Program has one and only one image
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * A Program has one and only one image
     *
     * @return Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Tells if instance of Episode has id or not
     *
     * @return boolean 
     */
    private function hasId()
    {
        if (is_null($this->id)) {
            return false;
        }

        return true;
    }

    /**
     * Gets program folder
     *
     * @return string
     */
    private function getFolder()
    {
        return $this->program->folder;
    }
}
