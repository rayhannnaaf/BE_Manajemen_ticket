<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KategoriKonserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    public function __construct(private readonly KategoriKonserService $service)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->getAll();
        return response()->json($data);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $kategori = $this->service->create($request->all());
        return response()->json($kategori, 201);
    }

    public function show(int $id): JsonResponse
    {
        $kategori = $this->service->find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }
        return response()->json($kategori);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $kategori = $this->service->update($id, $request->all());
        return response()->json($kategori);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}