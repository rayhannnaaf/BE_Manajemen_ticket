<?php

namespace App\Contracts;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

interface TicketServiceInterface
{
    public function getAll(): Collection;
    public function create(array $data): Ticket;
    public function find(int $id): ?Ticket;
    public function update(int $id, array $data): ?Ticket;
    public function delete(int $id): bool;
}