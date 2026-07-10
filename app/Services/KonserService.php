<?php

namespace App\Services;

use App\Contracts\KonserServiceInterface;
use App\Models\Konser;
use Illuminate\Database\Eloquent\Collection;

class KonserService implements KonserServiceInterface
{
    public function getAll(): Collection
    {
        return Konser::with('kategori')->latest()->get();
    }

    public function create(array $data): Konser
    {
        return Konser::create($data);
    }

    public function find(int $id): ?Konser
    {
        return Konser::find($id);
    }

    public function update(int $id, array $data): ?Konser
    {
        $konser = Konser::find($id);
        if (!$konser) {
            return null;
        }
        $konser->update($data);
        return $konser;
    }

    public function delete(int $id): bool
    {
        $konser = Konser::find($id);
        if (!$konser) {
            return false;
        }
        return $konser->delete();
    }
}