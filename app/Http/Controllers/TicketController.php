<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(private readonly TicketRepositoryInterface $repository)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->filled('user_id')) {
            return TicketResource::collection(
                $this->repository->findByUser((int) $request->query('user_id'))
            );
        }

        return TicketResource::collection($this->repository->all());
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->repository->create($request->validated());

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): TicketResource
    {
        return new TicketResource($this->repository->find($id) ?? abort(404));
    }

    public function update(UpdateTicketRequest $request, int $id): TicketResource
    {
        return new TicketResource($this->repository->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }
}
