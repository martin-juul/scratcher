<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Artwork
 *
 * @property string $id
 * @property string $basename
 * @property string $mime
 * @property int $size
 * @property int $height
 * @property int $width
 * @property string|null $model_type
 * @property string|null $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $model
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereBasename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artwork whereWidth($value)
 * @mixin \Eloquent
 */
class Artwork extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'basename',
        'mime',
        'size',
        'height',
        'width',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function (Artwork $model) {
            Storage::disk('artwork')->delete($model->basename);
        });
    }

    /**
     * Get the filesystem path to the image.
     *
     * @param bool $public
     * @return string
     */
    public function getPath(bool $public = true): string
    {
        $disk = $public ? 'artwork-public' : 'artwork';

        return Storage::disk($disk)->url($this->basename);
    }

    public function model()
    {
        return $this->morphTo();
    }
}
