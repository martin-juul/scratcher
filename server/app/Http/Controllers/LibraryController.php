<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{LibraryCreateRequest, LibraryUpdateRequest};
use App\Http\Resources\LibraryResource;
use App\Jobs\LibraryScan;
use App\Models\Library;

class LibraryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Library::class, 'library');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $libraries = Library::orderBy('name')->paginate();

        return LibraryResource::collection($libraries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LibraryCreateRequest $request
     * @return LibraryResource
     */
    public function store(LibraryCreateRequest $request)
    {
        $library = Library::create($request->validated());

        return new LibraryResource($library);
    }

    /**
     * Display the specified resource.
     *
     * @param Library $library
     * @return LibraryResource
     */
    public function show(Library $library)
    {
        return new LibraryResource($library);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LibraryUpdateRequest $request
     * @param Library $library
     * @return LibraryResource
     */
    public function update(LibraryUpdateRequest $request, Library $library)
    {
        $library->update($request->validated());

        return new LibraryResource($library);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Library $library
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Library $library)
    {
        $library->delete();

        return response(null, 204);
    }

    public function scan(Library $library)
    {
        $this->dispatch(new LibraryScan($library));

        return response()->json(['message' => 'scan started']);
    }
}
