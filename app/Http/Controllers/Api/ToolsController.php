<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Validator;
use App\Tool;

class ToolsController extends Controller
{
    /**
     * Validation rules for store and update operations with a Tool
     *
     * @var array
     */
    private $validationRules = [
        'title'         =>  'required|min:2',
        'link'          =>  'required|min:3',
        'description'   =>  'required|min:3',
        'tags'          =>  'required',
    ];

    /**
     * Display a listing of Tools.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();

        if (request()->has('tag')) {
            $filtered = $user->tools()->whereJsonContains('tags', request('tag'))->get();

            return response()->json($filtered);
        }
        
        return response()->json($user->tools);
    }

    /**
     * Display the specified Tool.
     *
     * @param  Tool  $tool
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tool $tool)
    {
        $user = auth()->user();

        if ($tool->user->id == $user->getAuthIdentifier()) {
            return response()->json($tool);
        }

        return response()->json(['error' => "You're not allowed to view this tool."], 403);
    }
    
    /**
     * Store a newly created Tool in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $user = auth()->user();

        $rawContent = request()->getContent();
        if ((is_null(json_decode($rawContent, true))) && (json_last_error() != 'JSON_ERROR_NONE')) {
            return response()->json(['error' => 'Malformed JSON provided'], 422);
        }
        
        $validator = Validator::make(request()->all(), $this->validationRules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tool = $user->tools()->create(request()->all());

        return response()->json($tool, 201);
    }

    /**
     * Update the specified Tool in storage.
     *
     * @param  Tool  $tool
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Tool $tool)
    {
        $user = auth()->user();

        $rawContent = request()->getContent();
        if ((is_null(json_decode($rawContent, true))) && (json_last_error() != 'JSON_ERROR_NONE')) {
            return response()->json(['error' => 'Malformed JSON provided'], 422);
        }

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

    /**
     * Remove the specified Tool from storage.
     *
     * @param  Tool  $tool
     * @return \Illuminate\Http\JsonResponse
     */
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
