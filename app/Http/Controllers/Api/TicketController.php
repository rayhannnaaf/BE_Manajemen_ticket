<?php

namespace App\Http\Controllers\Api;

use App\Contracts\TicketServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketServiceInterface $service
    ) {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->getAll();
        return response()->json($data);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'konser_id'   => 'required|exists:konsers,id',
            'ticket_name' => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $ticket = $this->service->create($request->all());

        return response()->json($ticket, 201);
    }

    public function show(int $id): JsonResponse
    {
        $ticket = $this->service->find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], 404);
        }

        return response()->json($ticket);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'konser_id'   => 'sometimes|exists:konsers,id',
            'ticket_name' => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'status'      => 'sometimes|in:available,sold_out',
        ]);

        $ticket = $this->service->update($id, $request->all());

        if (!$ticket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], 404);
        }

        return response()->json($ticket);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Tiket berhasil dihapus']);
    }
}