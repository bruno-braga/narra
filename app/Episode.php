<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Audio;
use App\Image;
use App\Program;

use App\Traits\UploadTrait;
use App\Rss\RssBuilder;

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
        'title',
        'user_id',
        'program_id',
        'duration',
        'type',
        'size',
        'description'
    ];

    /**
     * Enables us to hook into model event's
     *
     * @return void 
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($episode) {
            if ($episode->title === 'null' || $episode->title === 'undefined') {
                $episode->title = NULL;
            }

            if ($episode->description === 'null' || $episode->description  === 'undefined') {
                $episode->description = NULL;
            }

            $episode->slug = Str::slug($episode->title, '-');
        });

        static::created(function($episode) {
            $episode->is_draft = $episode->getIsDraft();
            $episode->save();

            $episode->storeOnFileAndDb(self::$cover, 'images', $episode);
            $episode->storeOnFileAndDb(self::$file, 'audios', $episode);

            if (!$episode->is_draft) {
                $program = Program::select(['programs.id', 'programs.user_id', 'programs.title', 'programs.slug', 'programs.description', 'programs.folder'])
                    ->where('programs.user_id', Auth::id())
                    ->where('programs.slug', $episode->program->slug)
                    ->with([
                        'categories' => function($query) {
                            $query->select('categories.id', 'categories.name', 'categories.parent_id');
                        },
                        'episodes' => function($query) {
                            $query->select('episodes.id', 'episodes.program_id', 'title', 'slug', 'description', 'duration', 'size', 'type', 'updated_at')
                                ->where('is_draft', false)
                                ->with([
                                    'images' => function($query) {
                                        $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                                    },
                                    'audios' => function($query) {
                                        $query->select('audiable_id', DB::raw('CONCAT(audios.path, audios.filename) as path'));
                                    }
                                ]);
                        },
                        'images' => function($query) {
                            $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                        },
                        'settings' => function($query) {
                            $query->select('id', 'program_id', 'language_id', 'explicit')->with('language');
                        }
                    ])
                    ->get()
                    ->first();

                $dom = RssBuilder::build($program);

                Storage::disk('public')
                    ->put(substr($program->images->path, 8, 15) . '/rss', $dom->saveXML());
            }
        });

        static::updating(function($episode) {
            $episode->is_draft = $episode->getIsDraft();
            $episode->slug = Str::slug($episode->title, '-');
        });

        static::updated(function($episode) {
            if (!$episode->is_draft) {
                $program = Program::select(['programs.id', 'programs.user_id', 'programs.title', 'programs.slug', 'programs.description', 'programs.folder'])
                    ->where('programs.user_id', Auth::id())
                    ->where('programs.slug', $episode->program->slug)
                    ->with([
                        'categories' => function($query) {
                            $query->select('categories.id', 'categories.name', 'categories.parent_id');
                        },
                        'episodes' => function($query) {
                            $query->select('episodes.id', 'episodes.program_id', 'title', 'slug', 'description', 'duration', 'size', 'type', 'updated_at')
                                ->where('is_draft', false)
                                ->with([
                                    'images' => function($query) {
                                        $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                                    },
                                    'audios' => function($query) {
                                        $query->select('audiable_id', DB::raw('CONCAT(audios.path, audios.filename) as path'));
                                    }
                                ]);
                        },
                        'images' => function($query) {
                            $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                        },
                        'settings' => function($query) {
                            $query->select('id', 'program_id', 'language_id', 'explicit')->with('language');
                        }
                    ])
                    ->get()
                    ->first();

                $dom = RssBuilder::build($program);

                Storage::disk('public')
                    ->put(substr($program->images->path, 8, 15) . '/rss', $dom->saveXML());
            }
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
        return $this->morphOne(Audio::class, 'audiable');
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
		 * Get the route key for the model.
		 *
		 * @return string
		 */
    public function getRouteKeyName()
    {
        return 'slug';
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

    private function getIsDraft()
    {
        $isDraft = (
            is_null($this->title) ||
            is_null($this->description) ||
            !$this->images()->exists() ||
            !$this->audios()->exists()
        );

        return $isDraft;
    }
}
