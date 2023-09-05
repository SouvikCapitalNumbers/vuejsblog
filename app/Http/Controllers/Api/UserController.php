<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser()
    {
        return response()->json([
            'status' => true,
            'data' => auth()->user()->toArray()
        ], 200);
    }
}
