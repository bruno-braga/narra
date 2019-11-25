<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
     /**
     * Concatanates $entity->id with filename
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\Program || \App\Episode $entity
     *
     * @return string 
     */
    private function createFileName($file, $entity)
    {
        return $entity->id . '_' . $file->getClientOriginalName();
    } 


     /**
     * Removes file from public disk
     *
     * @param string $imgPath
     *
     */
    public function destroyFile($imgPath)
    {
        // get's string from 8 index till the end
        $imgPath = substr($imgPath, 8);
        return Storage::disk($this->getConstants()->publicDisk)->delete($imgPath);
    }

    /**
     * Returns an array with the path to save
     * the file on the disk and also a path to
     * save on database
     *
     * The path saved on database will be used to
     * display the image on the website
     *
     * @param  string  $folderName 
     *
     * @return array
     */
    private function createPath($folderName)
    {
        $paths = new \stdClass();

        $const = $this->getConstants();

        $paths->saveDisk = '/' . $const->publicDisk . '/' . $folderName . '/';
        $paths->saveDb = $const->publicFolder . $folderName . '/';

        return $paths;
    }

    /**
     * Stores the file on the public folder
     * and creates a DB register based on $type
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  String  $type 'images' || 'audios'
     * @param  Model $entity Image || Audio
     *
     * @return false || string
     */
    private function storeOnFileAndDb($file, $type = null)
    {
        if (is_null($file)) {
            return false;
        }

        $fileName = $this->createFileName($file, $this);
        $path = $this->createPath($this->getFolder());

        $this->{$type}()->create([
            'path' => $path->saveDb,
            'filename' => $fileName,
        ]);

        return $file->storeAs($path->saveDisk, $fileName);
    }

    /**
     * Updates database entity and file
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string  $type 'images' || 'audios'
     *
     * @return false || string
     */
    private function updateFileAndDb($file, $type = null)
    {
        if (!$this->hasId()) {
            return;
        }

        $hasSavedFile = collect($this->{$type})->isNotEmpty();
        $hasNewFile = !is_null($file);

        if ($hasNewFile) {
            $fileName = $this->createFileName($file, $this);
            $path = $this->createPath($this->getFolder($this));

            if (!$hasSavedFile) {
                $this->{$type}()->create([
                  'path' => $path->saveDb,
                  'filename' => $fileName,
                ]);
               
                $file->storeAs($path->saveDisk, $fileName);

                return;
            }

            if ($hasSavedFile) {
                $filePath = $this->getFolder($this) . '/' . $this->{$type}->first()->filename;
                Storage::disk($this->getConstants()->publicDisk)->delete($filePath);
                $file->storeAs($path->saveDisk, $fileName);
            }

            $this->{$type}()->update([
                'path' => $path->saveDb,
                'filename' => $fileName,
            ]);
        }
    }

    /**
     * Detele database entity and file
     *
     * @param  string  $type 'images' || 'audios'
     *
     */
    private function deleteFileAndDb($type)
    {
        $hasntRelationship = collect($this->{$type})->isEmpty();
        if ($hasntRelationship) {
            return false;
        }

        $typeProperty = Str::singular($type) . '_path';
        $path = $this->{$type}->{$typeProperty};

        $this->{$type}()->delete();
        $this->destroyFile($path);
    }
    
    /**
     * Returns a stdClass containing some constants
     * that are used when dealing with fs operations
     *
     * @return stdClass
     *
     */
    private function getConstants()
    {
        $std = new \stdClass();

        $std->publicDisk = 'public';
        $std->publicFolder = '/storage/';
        $std->defaultPodcastImg = 'default-podcast.png';

        return $std;
    }
}
