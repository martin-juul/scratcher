<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Playlist
 *
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Track[] $tracks
 * @property-read int|null $tracks_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PlaylistFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Playlist whereUserId($value)
 * @mixin \Eloquent
 */
class Playlist extends AbstractModel
{
    use HasFactory;

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'track_playlist')
            ->withPivot(['order'])
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
