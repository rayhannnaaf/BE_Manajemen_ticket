<?php
namespace App\Http\Controllers\Api;

use App\Contracts\OrderServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $service
    ) {
    }

    /**
     * Untuk admin: melihat semua order (buat di-approve/reject via checkbox)
     */
    public function index(): JsonResponse
    {
        return response()->json($this->service->getAll());
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->service->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        return response()->json($order);
    }

    /**
     * Untuk user di landing page: mengisi order
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        try {
            $order = $this->service->createOrder([
                'user_id'    => $request->user()->id, // pastikan route pakai auth middleware
                'ticket_id'  => $request->input('ticket_id'),
                'quantity'   => $request->input('quantity'),
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($order, 201);
    }

    /**
     * Untuk admin: centang approve
     */
    public function approve(int $id): JsonResponse
    {
        try {
            $order = $this->service->approve($id);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($order);
    }

    /**
     * Untuk admin: reject
     */
    public function reject(int $id): JsonResponse
    {
        try {
            $order = $this->service->reject($id);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Order berhasil ditolak', 'order' => $order]);
    }
}