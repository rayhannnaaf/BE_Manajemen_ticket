<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Repositories\Contracts\KategoriRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KategoriController extends Controller
{
    // Depends on the interface only (DIP) — Laravel injects the bound implementation.
    public function __construct(private readonly KategoriRepositoryInterface $repository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return KategoriResource::collection($this->repository->all());
    }

    public function store(StoreKategoriRequest $request): JsonResponse
    {
        $kategori = $this->repository->create($request->validated());

        return (new KategoriResource($kategori))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): KategoriResource
    {
        return new KategoriResource($this->repository->find($id) ?? abort(404));
    }

    public function update(UpdateKategoriRequest $request, int $id): KategoriResource
    {
        return new KategoriResource($this->repository->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }
}