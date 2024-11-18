<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);  // Trả về một người dùng
    }
    
}
