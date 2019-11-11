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

    public static $file;
    public static $cover;

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

        static::created(function($episode) {
            $path = self::createFolderPath($episode->program->folder);
            $folderPath = self::createPublicFolderPath($episode->program->folder);

            if (!is_null(self::$file)) {
                $fileName = self::createFileName(self::$file, $episode);

                $episode->audios()->create([
                    'path' => $path,
                    'filename' => $fileName,
                ]);


                self::$file->storeAs($folderPath, $fileName);
            }

            if (!is_null(self::$cover)) {
                $fileName = self::createFileName(self::$cover, $episode);

                $episode->images()->create([
                    'path' => $path,
                    'filename' => $fileName
                  ]);

                self::$cover->storeAs($folderPath, $fileName);
            }
        });

        static::saving(function($episode) {
            $hasAudio = $episode->audios()->get()->count() > 0; 
            $hasNewAudio= !is_null(self::$file);

            if ($hasNewAudio) {
                $path= '/storage/' . $episode->program->folder . '/';
                $fileName = $episode->id . '_' . self::$file->getClientOriginalName();

                if ($hasAudio) {
                    Storage::disk(self::$PUBLIC)->delete($episode->program->folder . '/' . $episode->audios->first()->filename);
                    $folderPath = self::createPublicFolderPath($episode->program->folder);
                    self::$file->storeAs($folderPath, $fileName);
                }

                $episode->audios()->update([
                    'path' => $path,
                    'filename' => $fileName
                ]);
            }

            $hasCover= $episode->images()->get()->count() > 0; 
            $hasNewCover = !is_null(self::$cover);

            if ($hasNewCover) {
                $path= '/storage/' . $episode->program->folder . '/';
                $fileName = $episode->id . '_' . self::$cover->getClientOriginalName();

                if ($hasAudio) {
                    Storage::disk(self::$PUBLIC)->delete($episode->program->folder . '/' . $episode->images->first()->filename);
                    $folderPath = self::createPublicFolderPath($episode->program->folder);
                    self::$cover->storeAs($folderPath, $fileName);
                }

                $episode->images()->update([
                    'path' => $path,
                    'filename' => $fileName
                ]);
            }
        });

        static::deleting(function($episode) {
            $audioPath = $episode->audios->first()->audio_path;
            $imagePath = $episode->images->first()->image_path;


            $episode->audios()->delete();
            $episode->images()->delete();

            $episode->destroyFile($audioPath);
            $episode->destroyFile($imagePath);
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

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    private static function getUniqFolderName()
    {
        $folderName = NULL; 
        do {
          $folderName = uniqid();
        } while(Storage::exists($folderName));

        return $folderName;
    }
}
