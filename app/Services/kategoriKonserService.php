<?php

namespace App\Services;

use App\Models\Kategori;
use App\Contracts\KategoriKonserServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class KategoriKonserService implements KategoriKonserServiceInterface
{
    public function getAll(): Collection
    {
        return Kategori::all();
    }

    public function create(array $data): Kategori
    {
        $data['name'] = trim($data['name']);
        return Kategori::create($data);
    }

    public function find(int $id): ?Kategori
    {
        return Kategori::find($id);
    }

    public function update(int $id, array $data): ?Kategori
    {
        $kategori = $this->find($id);
        if (!$kategori) {
            return null;
        }

        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }
        
        $kategori->update($data);
        return $kategori->fresh();
    }

    public function delete(int $id): bool
    {
        $kategori = $this->find($id);
        if (!$kategori) {
            return false;
        }
        return (bool) $kategori->delete();
    }
}