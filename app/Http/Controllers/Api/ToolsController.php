<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Tool;

class ToolsController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        if (request()->has('tag')) {
            $filtered = $user->tools()->whereJsonContains('tags', request('tag'))->get();

            return response()->json($filtered);
        }
        
        return response()->json($user->tools);
    }

    public function show(Tool $tool)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        if ($tool->user->id == $user->getAuthIdentifier()) {
            return response()->json($tool);
        }

        return response()->json(['error' => "You're not allowed to view this tool."], 403);
    }
    
    public function store()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        $attributes = $this->validateToolAttributes();

        $tool = $user->tools()->create($attributes);

        return response()->json($tool, 201);
    }

    public function update(Tool $tool)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        if ($tool->user->id == $user->getAuthIdentifier()) {
            $attributes = $this->validateToolAttributes();

            $tool->update($attributes);

            return response()->json([], 204);
        }

        return response()->json(['error' => "You're not allowed to update this tool."], 403);
    }

    public function destroy(Tool $tool)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        if ($tool->user->id == $user->getAuthIdentifier()) {
            $tool->delete();

            return response()->json([], 204);
        }

        return response()->json(['error' => "You're not allowed to delete this tool."], 403);
    }

    private function validateToolAttributes()
    {
        return request()->validate([
            'title'         =>  'required|min:2',
            'link'          =>  'required|min:3',
            'description'   =>  'required|min:3',
            'tags'          =>  'required',
        ]);
    }
}
