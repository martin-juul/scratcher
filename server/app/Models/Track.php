<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Track
 *
 * @property string $id
 * @property string $album_id
 * @property string $title
 * @property string $sha256
 * @property string $path
 * @property string|null $file_format
 * @property string|null $mime_type
 * @property string|null $isrc
 * @property int|null $file_size
 * @property int|null $bitrate
 * @property int|null $length
 * @property int|null $track_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Album $album
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person[] $artists
 * @property-read int|null $artists_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read int|null $genres_count
 * @method static \Database\Factories\TrackFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Track newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track query()
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereBitrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereFileFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereIsrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereSha256($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereTrackNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Track extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sha256',
        'path',
        'file_format',
        'file_size',
        'mime_type',
        'isrc',
        'bitrate',
        'length',
        'track_number',
    ];

    public function getRouteKeyName(): string
    {
        return 'sha256';
    }

    public function artists()
    {
        return $this->belongsToMany(Person::class, 'people_track')
            ->withPivot('role')
            ->withTimestamps()
            ->wherePivot('role', '=', 'artist');
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_track')
            ->withTimestamps();
    }

}
