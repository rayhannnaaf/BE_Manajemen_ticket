<?php
namespace App\Contracts;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function getAll(): Collection;
    public function find(int $id): ?Order;
    public function createOrder(array $data): Order;
    public function approve(int $id): Order;
    public function reject(int $id): Order;
}