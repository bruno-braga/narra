<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Image;
use App\Episode;

use App\Traits\UploadTrait;


class Program extends Model
{
  use UploadTrait;

  protected $fillable = ['title', 'user_id', 'description'];

  public static $file;

  public static function boot()
  {
      parent::boot();

      static::creating(function($program) {
        $program->slug = Str::slug($program->title, '-');
      });

      static::created(function($program) {
          $folderName = NULL; 
          do {
            $folderName = uniqid();
          } while(Storage::exists($folderName));

          $path = '/storage/' . $folderName . '/';
          $fileName = $program->id . '_' . self::$file->getClientOriginalName();

          $program->images()->create([
              'path' => $path,
              'filename' => $fileName,
          ]);

          $program->saveFiles(self::$file, $folderName, $fileName);
      });

      static::saving(function($program) {
          $hasAtLeastAImage = $program->images()->get()->count() > 0; 
          $hasFile = !is_null(self::$file);

          if ($hasAtLeastAImage && $hasFile) {
              $oldImage = $program->images->image_path;

              $program->slug = Str::slug($program->title, '-');
              $folderName = substr($program->images->path, 8);
              $fileName = $program->id . '_' . self::$file->getClientOriginalName();

              $program->images()->update([
                  'filename' => $fileName,
              ]);

              $program->saveFiles(self::$file, $folderName, $fileName);
              $program->destroyFile($oldImage);

          }

          self::$file = NULL;
      });

      static::deleting(function($program) {
          $program->images()->delete();
      });
  }

  public function images()
  {
      return $this->morphOne(Image::class, 'imageable');
  }

  public function episodes()
  {
      return $this->hasMany(Episode::class);
  }

    public function getAudioPathAttribute()
    {
        return $this->path . '' . $this->filename;
    }

}
