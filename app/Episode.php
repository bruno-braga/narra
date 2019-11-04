<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Audio;
use App\Program;

use App\Traits\UploadTrait;

class Episode extends Model
{
    use UploadTrait;

    public static $file;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id', 'program_id', 'description'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($episode) {
            $path = $episode->audios->first()->audio_path;
            $episode->audios()->delete();
            $episode->destroyFile($path);
        });

        static::created(function($episode) {
            $path = $episode->program->images->path; 
            $fileName = $episode->id . '_' . self::$file->getClientOriginalName();

            $episode->audios()->create([
                'path' => $path,
                'filename' => $fileName,
            ]);

            $episode->saveFiles(self::$file, substr($path, 8), $fileName);
        });

        // When saving, if an audio already exists
        // means that we are updating our model
        // this is being done because if any info
        // on the model doesn't change the updating
        // event is not triggered
        static::saving(function($episode) {
            $hasAtLeastAImage = $episode->audios()->get()->count() > 0; 
            $hasFile = !is_null(self::$file);
            
            if ($hasAtLeastAImage && $hasFile) {
                $oldImage = $episode->audios->first()->audio_path;

                $folderName = substr($episode->audios->first()->path, 8);
                $fileName = $episode->id . '_' . self::$file->getClientOriginalName();

                $episode->audios()->update([
                    'filename' => $fileName,
                ]);

                $episode->saveFiles(self::$file, $folderName, $fileName);
                $episode->destroyFile($oldImage);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audios()
    {
        return $this->morphMany(Audio::class, 'audiable');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
