<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tool;

class ToolsController extends Controller
{
    public function index()
    {
        return response()->json(Tool::all());
    }

    public function show(Tool $tool)
    {
        return response()->json($tool);
    }
    public function store()
    {
        request()->validate([
            'title'         =>  'required|min:3',
            'link'          =>  'required|min:3',
            'description'   =>  'required|min:3',
            'tags'          =>  'nullable',
        ]);

        $tool = Tool::create(request()->all());

        return response()->json($tool, 201);
    }
}
