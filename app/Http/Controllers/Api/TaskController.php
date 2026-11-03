<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[OA\Get(
        path: "/tasks",
        summary: "Ambil daftar semua tugas milik pengguna yang login",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar tugas berhasil ditampilkan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Daftar tugas berhasil ditampilkan"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "user_id", type: "integer", example: 1),
                                    new OA\Property(property: "title", type: "string", example: "Mengerjakan LKPD Duo Developer"),
                                    new OA\Property(property: "description", type: "string", example: "Backend Laravel + Swagger"),
                                    new OA\Property(property: "status", type: "string", enum: ["todo", "done"], example: "todo"),
                                    new OA\Property(property: "due_date", type: "string", format: "date", example: "2026-11-05"),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Belum terautentikasi"),
        ]
    )]
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tugas berhasil ditampilkan',
            'data' => $tasks
        ], 200);
    }

    #[OA\Post(
        path: "/tasks",
        summary: "Tambah tugas baru",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Belajar Laravel Sanctum"),
                    new OA\Property(property: "description", type: "string", nullable: true, example: "Membaca dokumentasi Sanctum"),
                    new OA\Property(property: "status", type: "string", enum: ["todo", "done"], default: "todo", example: "todo"),
                    new OA\Property(property: "due_date", type: "string", format: "date", nullable: true, example: "2026-11-10"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Tugas berhasil ditambahkan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Tugas berhasil ditambahkan"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 2),
                                new OA\Property(property: "user_id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Belajar Laravel Sanctum"),
                                new OA\Property(property: "description", type: "string", example: "Membaca dokumentasi Sanctum"),
                                new OA\Property(property: "status", type: "string", example: "todo"),
                                new OA\Property(property: "due_date", type: "string", example: "2026-11-10"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validasi gagal"),
            new OA\Response(response: 401, description: "Belum terautentikasi"),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:todo,done',
            'due_date' => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditambahkan',
            'data' => $task
        ], 201);
    }

    #[OA\Get(
        path: "/tasks/{id}",
        summary: "Ambil detail satu tugas",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "ID Tugas", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detail tugas berhasil diambil",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Detail tugas berhasil diambil"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "user_id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Mengerjakan LKPD Duo Developer"),
                                new OA\Property(property: "description", type: "string", example: "Backend Laravel + Swagger"),
                                new OA\Property(property: "status", type: "string", example: "todo"),
                                new OA\Property(property: "due_date", type: "string", example: "2026-11-05"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Tugas tidak ditemukan atau tidak memiliki akses"),
            new OA\Response(response: 401, description: "Belum terautentikasi"),
        ]
    )]
    public function show(Request $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail tugas berhasil diambil',
            'data' => $task
        ], 200);
    }

    #[OA\Put(
        path: "/tasks/{id}",
        summary: "Perbarui data tugas",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "ID Tugas", schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Belajar Laravel Sanctum dan Swagger"),
                    new OA\Property(property: "description", type: "string", nullable: true, example: "Dokumentasi Sanctum dan OpenAPI"),
                    new OA\Property(property: "status", type: "string", enum: ["todo", "done"], example: "done"),
                    new OA\Property(property: "due_date", type: "string", format: "date", nullable: true, example: "2026-11-12"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Tugas berhasil diperbarui",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Tugas berhasil diperbarui"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Tugas tidak ditemukan"),
            new OA\Response(response: 401, description: "Belum terautentikasi"),
        ]
    )]
    public function update(Request $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:todo,done',
            'due_date' => 'sometimes|nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui',
            'data' => $task
        ], 200);
    }

    #[OA\Patch(
        path: "/tasks/{id}/status",
        summary: "Ubah cepat status tugas (todo / done)",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "ID Tugas", schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["status"],
                properties: [
                    new OA\Property(property: "status", type: "string", enum: ["todo", "done"], example: "done"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Status tugas berhasil diperbarui",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Status tugas berhasil diperbarui"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Tugas tidak ditemukan"),
            new OA\Response(response: 401, description: "Belum terautentikasi"),
        ]
    )]
    public function updateStatus(Request $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,done',
        ]);

        $task->update([
            'status' => $validated['status']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status tugas berhasil diperbarui',
            'data' => $task
        ], 200);
    }

    #[OA\Delete(
        path: "/tasks/{id}",
        summary: "Hapus tugas",
        tags: ["Tasks"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "ID Tugas", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tugas berhasil dihapus",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Tugas berhasil dihapus"),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Tugas tidak ditemukan"),
            new OA\Response(response: 401, description: "Belum terautentikasi"),
        ]
    )]
    public function destroy(Request $request, string $id)
    {
        $task = $request->user()->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus'
        ], 200);
    }
}
