<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{PlaylistCreateRequest, PlaylistUpdateRequest};
use App\Http\Resources\PlaylistResource;
use App\Models\{Playlist, Track, User};

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(User $user)
    {
        $playlists = $user->playlists()->latest()->paginate();

        return PlaylistResource::collection($playlists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlaylistCreateRequest $request
     * @param User $user
     * @return PlaylistResource
     */
    public function store(PlaylistCreateRequest $request, User $user)
    {
        $data = $request->validated();

        $playlist = Playlist::make([
            'name'      => $data['name'],
            'is_public' => $data['isPublic'],
        ]);
        $playlist->user()->associate($user);

        return new PlaylistResource($playlist);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return PlaylistResource
     */
    public function show(User $user, Playlist $playlist)
    {
        $playlist->loadMissing('tracks');

        return new PlaylistResource($playlist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlaylistUpdateRequest $request
     * @param User $user
     * @param Playlist $playlist
     * @return PlaylistResource
     */
    public function update(PlaylistUpdateRequest $request, User $user, Playlist $playlist)
    {
        $data = $request->validated();
        $tracks = [];

        foreach ($data['tracks'] as $track) {
            $model = Track::whereSha256($track['sha256'])->select('id')->first();

            if ($model) {
                $tracks[] = [$model->id => ['order' => $track['order']]];
            }
        }

        $playlist->tracks()->sync($tracks);

        return new PlaylistResource($playlist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @param Playlist $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Playlist $playlist)
    {
        $playlist->delete();

        return response(null, 204);
    }
}
