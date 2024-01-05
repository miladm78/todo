<?php

namespace App\Http\Controllers;

use App\Events\TodoStatusUpdated;
use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = Todo::query()->owned()->get();
        return new JsonResponse(['data' => $data], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $todo = Todo::query()->create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
        return new JsonResponse(['data' => $todo], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        if (auth()->user()->id != $todo->user_id) {
            return new JsonResponse(['error' => trans('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse(['data' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        if (auth()->user()->id != $todo->user_id) {
            return new JsonResponse(['error' => trans('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $data = $request->validated();
        $todo->update($data);
        return new JsonResponse(['data' => $todo],Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function make_complete_or_incomplete(Request $request, Todo $todo)
    {
        if ($request->user()->id != $todo->user_id) {
            return new JsonResponse(['error' => trans('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $todo->is_complete = !$todo->is_complete;
        $todo->save();
        event(new TodoStatusUpdated($todo->id, $todo->is_complete));
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        if (auth()->user()->id != $todo->user_id) {
            return new JsonResponse(['error' => trans('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $todo->delete();
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
