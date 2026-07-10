<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderRepositoryInterface $repository)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->filled('user_id')) {
            return OrderResource::collection(
                $this->repository->findByUser((int) $request->query('user_id'))
            );
        }

        return OrderResource::collection($this->repository->all());
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->repository->create($request->validated());

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): OrderResource
    {
        return new OrderResource($this->repository->find($id) ?? abort(404));
    }

    public function update(UpdateOrderRequest $request, int $id): OrderResource
    {
        return new OrderResource($this->repository->update($id, $request->validated()));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json(null, 204);
    }
}
