<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\{Album, Artwork, Genre, Library, Person, Track};
use App\Scanner\{FileResult, FileScanner};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\{Arr, Facades\Storage, Str};

class LibraryScan implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Library $library;

    /**
     * Create a new job instance.
     *
     * @param Library $library
     */
    public function __construct(Library $library)
    {
        $this->library = $library;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $scanner = new FileScanner($this->library->path);

        $scanner->listContents()->each(function (FileResult $file) use ($scanner) {
            if (!$file->isAudio() || Track::whereSha256($file->sha256)->exists()) {
                return;
            }

            $id3 = $scanner->parseId3($file);

            $albumName = Arr::first(Arr::get($id3, 'tags.id3v2.album')) ?: $file->directory;
            $album = Album::whereTitle($albumName)->first();
            if (!$album) {
                $album = $this->createAlbum($albumName, $id3);
            }

            $track = $this->makeTrack($id3, $file);
            $track->album()->associate($album);
            $track->save();

            $genres = Arr::get($id3, 'tags.id3v2.genre');
            if (is_array($genres)) {
                $this->saveGenres($genres, $track);
            }

            $people = Arr::get($id3, 'tags.id3v2.artist');
            if (is_array($people)) {
                $this->saveArtists($people, $track);
            }

            if (isset($id3['id3v2']['APIC']) && is_array($id3['id3v2']['APIC']) && $album->artwork()->count() < 1) {
                $this->saveArtwork($id3, $file, $album);
            }
        });
    }


    private function saveArtwork(array $id3, FileResult $file, Album $album): void
    {
        $picture = Arr::first(Arr::get($id3, 'id3v2.APIC'));

        if (!isset($picture['data'])) {
            return;
        }

        $filename = $file->sha256 . '.' . Str::after($picture['image_mime'], 'image/');
        $disk = Storage::disk('artwork');
        $disk->write($filename, $picture['data']);

        $artwork = Artwork::make([
            'basename' => $filename,
            'mime'     => $picture['image_mime'],
            'size'     => $disk->size($filename),
            'height'   => $picture['image_height'],
            'width'    => $picture['image_width'],
        ]);

        $artwork->model()->associate($album);

        if (!$artwork->save()) {
            $disk->delete($filename);
        }
    }

    private function saveArtists(array $people, Track $track): void
    {
        foreach ($people as $artists) {
            foreach (explode('/', $artists) as $artist) {
                $artist = Person::firstOrCreate(['name' => trim($artist)]);
                $track->artists()->attach($artist->id, ['role' => 'artist']);
            }
        }
    }

    private function createAlbum(mixed $albumName, array $id3): Album
    {
        $album = Album::make(['title' => $albumName, 'year' => Arr::first(Arr::get($id3, 'tags.id3v2.year'))]);
        $album->library()->associate($this->library);
        $album->save();

        $artist = Person::firstOrCreate(['name' => Arr::first(Arr::get($id3, 'tags.id3v2.band'))]);
        $album->artist()->attach($artist->id, ['role' => 'artist']);

        return $album;
    }

    private function saveGenres(array $genres, Track $track): void
    {
        foreach ($genres as $genre) {
            $model = Genre::whereName($genre)->first();
            if (!$model) {
                $model = Genre::create(['name' => $genre]);
            }

            $track->genres()->attach($model->id);
        }
    }

    private function makeTrack(array $id3, FileResult $file): Track
    {
        return Track::make([
            'title'        => Arr::first(Arr::get($id3, 'tags.id3v2.title')) ?? $file->basename,
            'sha256'       => $file->sha256,
            'path'         => $file->path,
            'file_format'  => Arr::get($id3, 'fileformat'),
            'file_size'    => (int)$file->size,
            'mime_type'    => $file->mime,
            'isrc'         => Arr::first(Arr::get($id3, 'tags.id3v2.isrc')),
            'bitrate'      => (int)Arr::get($id3, 'bitrate'),
            'length'       => (int)Arr::get($id3, 'playtime_seconds'),
            'track_number' => Arr::first(Arr::get($id3, 'tags.id3v2.track_number')),
        ]);
    }
}
