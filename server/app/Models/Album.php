<?php
declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\{Sluggable, SluggableScopeHelpers};
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Album
 *
 * @property string $id
 * @property string $library_id
 * @property string $title
 * @property string $slug
 * @property int|null $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person[] $artist
 * @property-read int|null $artist_count
 * @property-read \App\Models\Artwork|null $artwork
 * @property-read \App\Models\Library $library
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Track[] $tracks
 * @property-read int|null $tracks_count
 * @method static \Database\Factories\AlbumFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Album findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereLibraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
class Album extends AbstractModel
{
    use HasFactory, Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'title',
        'year',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title', 'year'],
            ],
        ];
    }

    public function setYearAttribute(string|int|null $value): void
    {
        if (!$value) {
            return;
        }

        $this->attributes['year'] = (int)$value;
    }

    public function artwork()
    {
        return $this->morphOne(Artwork::class, 'model');
    }

    public function artist()
    {
        return $this->belongsToMany(Person::class, 'people_album')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function library()
    {
        return $this->belongsTo(Library::class);
    }
}
