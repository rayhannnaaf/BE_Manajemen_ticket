<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonserKontroller extends Controller
{
    public function __construct(private readonly KonserRepositoryInterface $repository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return KonserResource::collection($this->repository->all());
    }

    public function store(StoreKonserRequest $request): JsonResponse
    {
        $konser = $this->repository->create($request->validated());

        return (new KonserResource($konser))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): KonserResource
    {
        return new KonserResource($this->repository->find($id) ?? abort(404));
    }

    public function update(UpdateKonserRequest $request, int $id): KonserResource
    {
        return new KonserResource($this->repository->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }
}
