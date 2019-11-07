<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{

    public static $PUBLIC = 'public';
    public static $STORAGE = '/storage/';
    public static $DEFAULT_IMG = 'default-podcast.png';

    public static function createFolderPath($folderName)
    {
        return self::$STORAGE . $folderName . '/';
    } 

    public static function createPublicFolderPath($folderName)
    {
        return '/' . self::$PUBLIC . '/' . $folderName . '/';
    } 

    public static function createFileName($file, $program)
    {
        return $program->id . '_' . $file->getClientOriginalName();
    } 

    public function destroyFile($imgPath)
    {
        // get's string from 8 index till the end
        $imgPath = substr($imgPath, 8);
        return Storage::disk('public')->delete($imgPath);
    }
}
