<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TrackResource;
use App\Models\{Album, Track};
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Album $album)
    {
        $album->loadMissing('tracks');

        return TrackResource::collection($album->tracks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album $album
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Album $album)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Album $album
     * @param \App\Models\Track $track
     * @return TrackResource
     */
    public function show(Album $album, Track $track)
    {
        return new TrackResource($track);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Album $album
     * @param \App\Models\Track $track
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album, Track $track)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Album $album
     * @param \App\Models\Track $track
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album, Track $track)
    {
        $track->delete();

        return response(null, 204);
    }
}
