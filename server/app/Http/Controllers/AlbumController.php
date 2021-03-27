<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AlbumUpdateRequest;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\{Request, Response};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class, 'album');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $albums = Album::with(['artist', 'artwork'])
            ->paginate($request->query('per_page', 25));

        return AlbumResource::collection($albums);
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     * @return AlbumResource
     */
    public function show(Album $album)
    {
        $album->loadMissing(['artist', 'artwork', 'tracks']);

        return new AlbumResource($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumUpdateRequest $request
     * @param Album $album
     * @return AlbumResource
     */
    public function update(AlbumUpdateRequest $request, Album $album)
    {
        $album->update($request->validated());

        $album->loadMissing(['artist', 'artwork', 'tracks']);

        return new AlbumResource($album);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     * @return Response
     * @throws \Exception
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return response(null, 204);
    }
}
