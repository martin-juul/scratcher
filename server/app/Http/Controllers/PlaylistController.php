<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{PlaylistCreateRequest, PlaylistUpdateRequest};
use App\Http\Resources\PlaylistResource;
use App\Models\{Playlist, Track};
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $playlists = $request->user()->playlists()->latest()->paginate();

        return PlaylistResource::collection($playlists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlaylistCreateRequest $request
     * @return PlaylistResource
     * @throws \Throwable
     */
    public function store(PlaylistCreateRequest $request)
    {
        $data = $request->validated();

        $playlist = Playlist::make([
            'name'      => $data['name'],
            'is_public' => $data['isPublic'],
        ]);
        $playlist->user()->associate($request->user());
        $playlist->saveOrFail();

        return new PlaylistResource($playlist);
    }

    /**
     * Display the specified resource.
     *
     * @param Playlist $playlist
     * @return PlaylistResource
     */
    public function show(Playlist $playlist)
    {
        $playlist->loadMissing('tracks');

        return new PlaylistResource($playlist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlaylistUpdateRequest $request
     * @param Playlist $playlist
     * @return PlaylistResource
     */
    public function update(PlaylistUpdateRequest $request, Playlist $playlist)
    {
        $data = $request->validated();
        $tracks = [];

        foreach ($data['tracks'] as $track) {
            $model = Track::whereSha256($track['sha256'])->select('id')->first();

            if ($model) {
                $tracks[$model->id] = ['sort' => $track['sort']];
            }
        }

        $playlist->tracks()->sync($tracks);

        return new PlaylistResource($playlist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Playlist $playlist
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Playlist $playlist)
    {
        $playlist->delete();

        return response(null, 204);
    }
}
