<?php

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService implements OrderServiceInterface
{
    public function getAll(): Collection
    {
        return Order::with(['ticket.konser', 'user'])->latest()->get();
    }

    public function find(int $id): ?Order
    {
        return Order::with(['ticket.konser', 'user'])->find($id);
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $ticket = Ticket::lockForUpdate()->findOrFail($data['ticket_id']);

            if ($ticket->status === 'sold_out' || $ticket->stock < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tiket tidak mencukupi.',
                ]);
            }

            return Order::create([
                'user_id'     => $data['user_id'],
                'ticket_id'   => $ticket->id,
                'image'       => $data['image'],
                'quantity'    => $data['quantity'],
                'total_price' => $ticket->price * $data['quantity'],
                'status'      => 'pending',
            ]);
        });
    }

    public function approve(int $id): Order
    {
        return DB::transaction(function () use ($id) {
            $order = Order::lockForUpdate()->findOrFail($id);

            if ($order->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Order ini sudah diproses sebelumnya.',
                ]);
            }

            $ticket = Ticket::lockForUpdate()->findOrFail($order->ticket_id);

            if ($ticket->stock < $order->quantity) {
                throw ValidationException::withMessages([
                    'stock' => 'Stok tidak mencukupi untuk approve order ini.',
                ]);
            }

            $ticket->decrement('stock', $order->quantity);
            if ($ticket->fresh()->stock <= 0) {
                $ticket->update(['status' => 'sold_out']);
            }

            $order->update(['status' => 'approve']);

            return $order->fresh(['ticket.konser', 'user']);
        });
    }

    public function reject(int $id): Order
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Order ini sudah diproses sebelumnya.',
            ]);
        }

        $order->update(['status' => 'reject']);

        return $order->fresh(['ticket.konser', 'user']);
    }
}