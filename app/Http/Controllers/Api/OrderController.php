<?php

namespace App\Http\Controllers\Api;

use App\Contracts\OrderServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $orders = $this->service->getAll();

        if ($request->filled('user_id')) {
            $orders = $orders->where('user_id', $request->query('user_id'))->values();
        }

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity'  => 'required|integer|min:1',
            'image'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $imagePath = $request->file('image')->store('payment-proofs', 'public');

            $order = $this->service->createOrder([
                'user_id'   => $request->user()->id,
                'ticket_id' => $request->ticket_id,
                'quantity'  => $request->quantity,
                'image'     => $imagePath,
            ]);

            return response()->json($order->load(['ticket.konser', 'user']), 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Gagal membuat order',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->service->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        return response()->json($order);
    }

    public function approve(int $id): JsonResponse
    {
        try {
            $order = $this->service->approve($id);
            return response()->json($order);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function reject(int $id): JsonResponse
    {
        try {
            $order = $this->service->reject($id);
            return response()->json($order);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $order = $this->service->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        if ($order->image) {
            Storage::disk('public')->delete($order->image);
        }

        $order->delete;

        return response()->json(['message' => 'Order berhasil dihapus']);
    }
}