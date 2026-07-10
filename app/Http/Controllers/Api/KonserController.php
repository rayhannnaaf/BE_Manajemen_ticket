<?php

namespace App\Http\Controllers\Api;

use App\Contracts\KonserServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KonserController extends Controller
{
    public function __construct(
        private readonly KonserServiceInterface $service
    ) {
    }

    /**
     * Menampilkan semua data konser
     */
    public function index(): JsonResponse
    {
        $data = $this->service->getAll();

        return response()->json($data);
    }

    /**
     * Menyimpan data konser baru
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'name'        => 'required|string|max:255',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $konser = $this->service->create($request->all());

        return response()->json($konser, 201);
    }

    /**
     * Menampilkan detail konser
     */
    public function show(int $id): JsonResponse
    {
        $konser = $this->service->find($id);

        if (!$konser) {
            return response()->json([
                'message' => 'Konser tidak ditemukan'
            ], 404);
        }

        return response()->json($konser);
    }

    /**
     * Update data konser
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'kategori_id' => 'sometimes|exists:kategori,id',
            'name'        => 'sometimes|string|max:255',
            'date'        => 'sometimes|date',
            'location'    => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $konser = $this->service->update($id, $request->all());

        if (!$konser) {
            return response()->json([
                'message' => 'Konser tidak ditemukan'
            ], 404);
        }

        return response()->json($konser);
    }

    /**
     * Hapus konser
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Konser tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Konser berhasil dihapus'
        ]);
    }
}   