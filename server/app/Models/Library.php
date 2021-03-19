<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\AbstractModel;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Library
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\LibraryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Library findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Library newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Library query()
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Library withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
class Library extends AbstractModel
{
    use HasFactory, Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'name',
        'path',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name'],
            ],
        ];
    }
}
