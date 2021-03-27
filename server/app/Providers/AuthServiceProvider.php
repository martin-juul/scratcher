<?php

namespace App\Providers;

use App\Models\{Album, Library, PersonalAccessToken, Playlist, Track, User};
use App\Policies\{AlbumPolicy, LibraryPolicy, PlaylistPolicy, TrackPolicy, UserPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Album::class    => AlbumPolicy::class,
        Library::class  => LibraryPolicy::class,
        Playlist::class => PlaylistPolicy::class,
        Track::class    => TrackPolicy::class,
        User::class     => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
