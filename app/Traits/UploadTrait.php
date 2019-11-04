<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
  public function saveFiles($file, $folderName, $fileName)
  {
      // $filename = $this->id . '_' . $file->getClientOriginalName();

      // $folderName = NULL;
      // switch(get_class($this)) {
        // case 'App\Program':
          // $folderName = Str::slug($this->title, '-');
        // break;
        // case 'App\Episode':
          // $folderName = Str::slug($this->program->title, '-');
        // break;
      // }

      // $saved = $this->{$type}()->{$operationType}([
          // 'path' => '/storage/' . $folderName . '/',
          // 'filename' => $filename,
      // ]);

      // return $this->store($file, $filename, $folderName);
    //
      return $file->storeAs('public/' . $folderName, $fileName);
  } 


  public function store($uploadedImage, $filename, $folderName)
  {
      $uploadedImage->storeAs('public/' . $folderName, $filename);
  }

  public function destroyFile($imgPath)
  {
      // get's string from 8 index till the end
      $imgPath = substr($imgPath, 8);
      Storage::disk('public')->delete($imgPath);
  }
}
