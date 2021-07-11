<?php
declare(strict_types=1);

namespace App\Jobs;

use App\MetaAudio\Mp3;
use App\Models\{Album, Artwork, Genre, Library, Person, Track};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\{Arr, Facades\File, Facades\Storage, LazyCollection, Str};
use Symfony\Component\Finder\SplFileInfo;

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
        $tagger = new \App\MetaAudio\Tagger;
        $tagger->addDefaultModules();

        $files = File::allFiles($this->library->path);
        $files = LazyCollection::make($files);

        $files->each(function (SplFileInfo $file) use ($tagger) {
            $hash = \Safe\sha1_file($file->getRealPath());
            if (!Str::startsWith('audio/', File::mimeType($file->getRealPath())) || Track::whereHash($hash)->exists()) {
                return;
            }

            $id3 = $tagger->open($file->getRealPath());

            $albumName = $id3->getAlbum();
            $album = Album::whereTitle($albumName)->first();
            if (!$album) {
                $album = $this->createAlbum($id3);
            }

            $track = $this->makeTrack($id3, $file->getRealPath(), $hash);
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


    private function saveArtwork(Mp3 $id3, SplFileInfo $file, Album $album): void
    {
        $picture = Arr::first(Arr::get($id3, 'id3v2.APIC'));

        if (!isset($picture['data'])) {
            return;
        }

        $filename = Has . '.' . Str::after($picture['image_mime'], 'image/');
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

    private function createAlbum(Mp3 $id3): Album
    {
        $album = Album::make(['title' => $id3->getTitle(), 'year' => $id3->getYear()]);
        $album->library()->associate($this->library);
        $album->save();

        $artist = Person::firstOrCreate($id3->getArtist());
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

    private function makeTrack(Mp3 $file, string $path, string $hash): Track
    {
        return Track::make([
            'title'        => $file->getTitle(),
            'hash'         => $hash,
            'path'         => $path,
            'file_size'    => File::size($path),
            'mime_type'    => File::mimeType($path),
            'isrc'         => $file->getIsrc(),
            'bitrate'      => $file->getBitrate(),
            'length'       => $file->getPlayTime(),
            'track_number' => $file->getTrackNumber(),
        ]);
    }
}
