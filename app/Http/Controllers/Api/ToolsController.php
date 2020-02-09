<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Validator;
use App\Tool;

class ToolsController extends Controller
{
    private $validationRules = [
        'title'         =>  'required|min:2',
        'link'          =>  'required|min:3',
        'description'   =>  'required|min:3',
        'tags'          =>  'required',
    ];

    public function index()
    {
        $user = auth()->user();

        if (request()->has('tag')) {
            $filtered = $user->tools()->whereJsonContains('tags', request('tag'))->get();

            return response()->json($filtered);
        }
        
        return response()->json($user->tools);
    }

    public function show(Tool $tool)
    {
        $user = auth()->user();

        if ($tool->user->id == $user->getAuthIdentifier()) {
            return response()->json($tool);
        }

        return response()->json(['error' => "You're not allowed to view this tool."], 403);
    }
    
    public function store()
    {
        $user = auth()->user();
        
        $validator = Validator::make(request()->all(), $this->validationRules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tool = $user->tools()->create(request()->all());

        return response()->json($tool, 201);
    }

    public function update(Tool $tool)
    {
        $user = auth()->user();

        if ($tool->user->id == $user->getAuthIdentifier()) {
            $validator = Validator::make(request()->all(), $this->validationRules);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $tool->update(request()->all());

            return response()->json([], 204);
        }

        return response()->json(['error' => "You're not allowed to update this tool."], 403);
    }

    public function destroy(Tool $tool)
    {
        $user = auth()->user();

        if ($tool->user->id == $user->getAuthIdentifier()) {
            $tool->delete();

            return response()->json([], 204);
        }

        return response()->json(['error' => "You're not allowed to delete this tool."], 403);
    }
}
