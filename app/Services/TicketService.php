<?php

namespace App\Services;

use App\Contracts\TicketServiceInterface;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketService implements TicketServiceInterface
{
    public function getAll(): Collection
    {
        return Ticket::with('konser')->latest()->get();
    }

    public function create(array $data): Ticket
    {
        if (isset($data['stock']) && (int) $data['stock'] === 0) {
            $data['status'] = 'sold_out';
        }

        return Ticket::create($data);
    }

    public function find(int $id): ?Ticket
    {
        return Ticket::with('konser')->find($id);
    }

    public function update(int $id, array $data): ?Ticket
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return null;
        }

        if (isset($data['stock']) && (int) $data['stock'] === 0 && !isset($data['status'])) {
            $data['status'] = 'sold_out';
        }

        $ticket->update($data);
        return $ticket->fresh('konser');
    }

    public function delete(int $id): bool
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return false;
        }
        return (bool) $ticket->delete();
    }
}