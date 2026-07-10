<?php

namespace App\Contracts;

use App\Models\Konser;
use Illuminate\Database\Eloquent\Collection;

interface KonserServiceInterface
{
    public function getAll(): Collection;
    public function create(array $data): Konser;
    public function find(int $id): ?Konser;
    public function update(int $id, array $data): ?Konser;
    public function delete(int $id): bool;
}