<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{PlaylistCreateRequest, PlaylistUpdateRequest};
use App\Http\Resources\PlaylistResource;
use App\Models\{Playlist, User};

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
        $playlist = Playlist::create($request->validated());

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
        $this->authorize();

        return response(null, 204);
    }
}
