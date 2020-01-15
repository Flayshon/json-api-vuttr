<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tool;

class ToolsController extends Controller
{
    public function index()
    {
        if (request()->has('tag')) {
            $filtered = Tool::whereJsonContains('tags', request('tag'))->get();

            return response()->json($filtered);
        }
        
        return response()->json(Tool::all());
    }

    public function show(Tool $tool)
    {
        return response()->json($tool);
    }
    
    public function store()
    {
        request()->validate([
            'title'         =>  'required|min:2',
            'link'          =>  'required|min:3',
            'description'   =>  'required|min:3',
            'tags'          =>  'required',
        ]);

        $tool = Tool::create(request()->all());

        return response()->json($tool, 201);
    }

    public function update(Tool $tool)
    {
        request()->validate([
            'title'         =>  'required|min:2',
            'link'          =>  'required|min:3',
            'description'   =>  'required|min:3',
            'tags'          =>  'required',
        ]);

        $tool->update(request()->all());

        return response()->json([], 204);
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();

        return response()->json([], 204);
    }
}
