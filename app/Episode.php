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

        static::created(function($episode) {
            if (!is_null(self::$file)) {
                $path = self::createFolderPath($episode->program->folder);
                $fileName = self::createFileName(self::$file, $episode);

                $episode->audios()->create([
                    'path' => $path,
                    'filename' => $fileName,
                ]);

                $folderPath = self::createPublicFolderPath($episode->program->folder);

                self::$file->storeAs($folderPath, $fileName);
            }
        });

      static::saving(function($episode) {
          $hasAtLeastAImage = $episode->audios()->get()->count() > 0; 
          $hasFile = !is_null(self::$file);

          if ($hasFile) {
              $path= '/storage/' . $episode->program->folder . '/';
              $fileName = $episode->id . '_' . self::$file->getClientOriginalName();

              if ($hasAtLeastAImage) {
                  Storage::disk(self::$PUBLIC)->delete($episode->program->folder . '/' . $episode->audios->first()->filename);
                  $folderPath = self::createPublicFolderPath($episode->program->folder);
                  self::$file->storeAs($folderPath, $fileName);
              }

              $episode->audios()->update([
                  'path' => $path,
                  'filename' => $fileName
              ]);
          }
      });

      static::deleting(function($program) {
          $program->images()->delete();
      });


        static::deleting(function($episode) {
            $path = $episode->audios->first()->audio_path;
            $episode->audios()->delete();
            $episode->destroyFile($path);
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

    private static function getUniqFolderName()
    {
        $folderName = NULL; 
        do {
          $folderName = uniqid();
        } while(Storage::exists($folderName));

        return $folderName;
    }
}
