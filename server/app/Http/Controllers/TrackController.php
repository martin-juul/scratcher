<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TrackResource;
use App\Models\{Album, Track};
use Exception;
use Illuminate\Http\{Request, Response};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class TrackController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Track::class, 'track');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Album $album
     * @return AnonymousResourceCollection
     */
    public function index(Album $album)
    {
        $album->loadMissing('tracks', 'tracks.album.artwork');

        return TrackResource::collection($album->tracks);
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     * @param Track $track
     * @return TrackResource
     */
    public function show(Album $album, Track $track)
    {
        return new TrackResource($track);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Album $album
     * @param Track $track
     * @return Response
     */
    public function update(Request $request, Album $album, Track $track)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     * @param Track $track
     * @return Response
     * @throws Exception
     */
    public function destroy(Album $album, Track $track)
    {
        $track->delete();

        return response(null, 204);
    }
}
