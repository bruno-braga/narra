<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Image;
use App\Setting;
use App\Episode;
use App\Category;

use App\Traits\UploadTrait;


class Program extends Model
{
    use UploadTrait;

    /**
     * Uploaded image.
     *
     * @var UploadedFile
     */
    public static $file;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'user_id', 'description'];

    /**
     * Enables us to hook into model event's
     *
     * @return void 
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($program) {
            $program->folder = $program->generateUniqFolderName();
            $program->slug = Str::slug($program->title, '-');

            Storage::disk($program->getConstants()->publicDisk)->makeDirectory($program->folder);
        });

        static::updating(function($program) {
            $program->slug = Str::slug($program->title, '-');
        });

        static::created(function($program) {
            $program->storeOnFileAndDb(self::$file, 'images', $program);
        });

        static::saving(function($program) {
            $program->updateFileAndDb(self::$file, 'images', $program);
        });

        static::deleting(function($program) {
            $program->deleteFileAndDb('images');
        });
    }

    public function users()
    {
        return $this->belongsTo(User::class);
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
     * A Program has one Setting
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(Setting::class);
    }

    /**
     * A Program has one or many episodes
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_program');
    }

    /**
     * Accessor to get full file path
     *
     * @return string
     */
    public function getAudioPathAttribute()
    {
        return $this->path . '' . $this->filename;
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

    /**
     * Tells if instance of Program has id or not
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
        return $this->folder;
    }

    /**
     * Generates folder name using uniqid()
     *
     * @return string
     */
    private function generateUniqFolderName()
    {
        $folderName = NULL; 
        do {
          $folderName = uniqid();
        } while(Storage::exists($folderName));

        return $folderName;
    }
}
