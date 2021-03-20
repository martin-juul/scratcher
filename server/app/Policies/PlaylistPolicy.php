<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\{Playlist, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function view(User $user, Playlist $playlist)
    {
        return $playlist->is_public;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->exists;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function update(User $user, Playlist $playlist)
    {
        return $playlist->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function delete(User $user, Playlist $playlist)
    {
        return $playlist->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function restore(User $user, Playlist $playlist)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return mixed
     */
    public function forceDelete(User $user, Playlist $playlist)
    {
        return $user->isAdmin();
    }
}
