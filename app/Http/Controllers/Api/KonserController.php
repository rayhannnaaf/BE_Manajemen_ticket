<?php

namespace App\Http\Controllers\Api;

use App\Contracts\KonserServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class KonserController extends Controller
{
    public function __construct(
        private readonly KonserServiceInterface $service
    ) {
    }

    public function index(): JsonResponse
    {
        $data = $this->service->getAll();
        return response()->json($data);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'name'        => 'required|string|max:255',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('konser', 'public');
        }

        $konser = $this->service->create($data);

        return response()->json($konser, 201);
    }

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

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'kategori_id' => 'sometimes|exists:kategori,id',
            'name'        => 'sometimes|string|max:255',
            'date'        => 'sometimes|date',
            'location'    => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $konser = $this->service->find($id);

        if (!$konser) {
            return response()->json([
                'message' => 'Konser tidak ditemukan'
            ], 404);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {

            if (!empty($konser->image) && Storage::disk('public')->exists($konser->image)) {
                Storage::disk('public')->delete($konser->image);
            }

            $data['image'] = $request->file('image')->store('konser', 'public');
        }

        $konser = $this->service->update($id, $data);

        return response()->json($konser);
    }

    public function destroy(int $id): JsonResponse
    {
        $konser = $this->service->find($id);

        if (!$konser) {
            return response()->json([
                'message' => 'Konser tidak ditemukan'
            ], 404);
        }

        if (!empty($konser->image) && Storage::disk('public')->exists($konser->image)) {
            Storage::disk('public')->delete($konser->image);
        }

        $this->service->delete($id);

        return response()->json([
            'message' => 'Konser berhasil dihapus'
        ]);
    }
}