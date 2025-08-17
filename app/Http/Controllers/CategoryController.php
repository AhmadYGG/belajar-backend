<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Ambil semua kategori",
     *     @OA\Response(response=200, description="Berhasil ambil daftar kategori")
     * )
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Tambah kategori baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Elektronik"),
     *             @OA\Property(property="description", type="string", example="Kategori untuk barang elektronik")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil menambah kategori")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        return Category::create($request->all());
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Ambil detail kategori",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Berhasil ambil detail kategori")
     * )
     */
    public function show($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update kategori",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Elektronik"),
     *             @OA\Property(property="description", type="string", example="Kategori untuk barang elektronik")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Berhasil update kategori")
     * )
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $category;
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Hapus kategori",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Berhasil hapus kategori")
     * )
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return response()->noContent();
    }
}