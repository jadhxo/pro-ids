<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyLearningController extends Controller
{
    public function index(Request $request)
    {
        // TEMP stub response so frontend doesn't crash
        return response()->json([
            'courses' => [],
            'progress' => [],
        ]);
    }
}