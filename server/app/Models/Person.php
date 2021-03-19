<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\AbstractModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Person
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Artwork[] $artwork
 * @property-read int|null $artwork_count
 * @method static \Database\Factories\PersonFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Person findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Person withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
class Person extends AbstractModel
{
    use HasFactory, Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name',
        'description',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name'],
            ],
        ];
    }

    public function artwork()
    {
        return $this->morphMany(Artwork::class, 'model');
    }
}
