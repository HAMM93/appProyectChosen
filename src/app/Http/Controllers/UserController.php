<?php

namespace App\Http\Controllers;

use App\Helpers\Response\ResponseAPI;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all())->save();

        return ResponseAPI::created(['user' => ['id' => $user->id]]);
    }

}
