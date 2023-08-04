<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $notes = Note::all();
        return response()->json(["data" => NoteResource::collection($notes)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request): JsonResponse
    {
        $newNote = Note::create($request->all());
        return response()->json(["data" => new NoteResource($newNote)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {

            $note = Note::find($id);

            if (!$note) {
                return response()->json(["error" => "Note not found"], 404);
            }

            return response()->json(["data" => new NoteResource($note)], 200);

        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, string $id): JsonResponse
    {
        try {

            $note = Note::find($id);

            if (!$note) {
                return response()->json(["error" => "Note not found"], 404);
            }

            $note->title = $request->title;
            $note->content = $request->content;
            $note->save();

            return response()->json(["data" => new NoteResource($note)], 200);

        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {

            $note = Note::find($id);

            if (!$note) {
                return response()->json(["error" => "Note not found"], 404);
            }

            $note->delete();
            return response()->json(status: 204);

        } catch (\Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }

        return response()->json(status: 204);
    }
}
