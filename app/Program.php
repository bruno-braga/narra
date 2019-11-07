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
          $program->folder = self::getUniqFolderName();
          $program->slug = Str::slug($program->title, '-');

          Storage::disk(self::$PUBLIC)->makeDirectory($program->folder);
      });

      static::created(function($program) {
          if (!is_null(self::$file)) {
              $path = self::createFolderPath($program->folder);
              $fileName = self::createFileName(self::$file, $program);

              $program->images()->create([
                  'path' => $path,
                  'filename' => $fileName,
              ]);

              $folderPath = self::createPublicFolderPath($program->folder);

              return self::$file->storeAs($folderPath, $fileName);
          }

          $program->images()->create([
              'path' => '/storage/',
              'filename' => 'default-podcast.png',
          ]);
      });

      static::saving(function($program) {
          $hasAtLeastAImage = $program->images()->get()->count() > 0; 
          $hasFile = !is_null(self::$file);

          if ($hasFile) {
              $program->slug = Str::slug($program->title, '-');
              $path= '/storage/' . $program->folder . '/';
              $fileName = $program->id . '_' . self::$file->getClientOriginalName();

              if ($hasAtLeastAImage) {
                  Storage::disk(self::$PUBLIC)->delete($program->folder . '/' . $program->images->filename);
                  $folderPath = self::createPublicFolderPath($program->folder);
                  self::$file->storeAs($folderPath, $fileName);
              }

              $program->images()->update([
                  'path' => $path,
                  'filename' => $fileName
              ]);
          }
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

  private static function getUniqFolderName()
  {
      $folderName = NULL; 
      do {
        $folderName = uniqid();
      } while(Storage::exists($folderName));

      return $folderName;
  }
}
