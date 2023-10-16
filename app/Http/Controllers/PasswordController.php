<?php

namespace App\Http\Controllers;

use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller

{
    public function index()
    {
        $passwords = Password::all();

        return response()->json([
            'passwords' => $passwords,
        ]);
    }

    public function store(Request $request)
    {
        $validaror = Validator::make($request->all(), [
            'type' => 'required|string',
            'description' => 'required|string',
            'user' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validaror->fails()){
            return response()->json([
                'status' => 422,
                'error' => $validaror->messages()
            ], 422);

        }else{
            $password = new Password;
            $password->type = $request->type;
            $password->description = $request->description;
            $password->user = $request->user;
            $password->password = bcrypt($request->password);

            $password->save();

            if($password){

                return response()->json([
                    'status' => 200,
                    'message' => 'Password created succesfully'
                ], 200);
            }else{

                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong'
                ], 500);
            }
        }
    }

}

