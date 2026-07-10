<?php

namespace App\Contracts;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Collection;

interface KategoriKonserServiceInterface
{
    public function getAll(): Collection;
    public function create(array $data): Kategori;
    public function find(int $id): ?Kategori;
    public function update(int $id, array $data): ?Kategori;
    public function delete(int $id): bool;
}