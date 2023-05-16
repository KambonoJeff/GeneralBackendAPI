<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;




class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request ){
        $field = $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string','min:6']

        ]);

        $user = User::where('email', $field['email'] )->first();
        if(!$user || !Hash::check($field['password'], $user->password)){
            return $this->error('','you are not authorized to make this request', 403);
        }
        else{
            return $this->success([
                'user'=>$user,
                 'token'=> $user->createToken('API Token for user ')->plainTextToken
             ]);
        }


    }
    public function register(Request $request){
        $field = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required','string', 'confirmed'],
        ]);
        $user = User::create([
            'name'=>$field['name'],
            'email'=>$field['email'],
            'password'=>bcrypt($field['password']),
        ]);
        return $this->success([
            'user'=> $user,
            'token'=>$user->createToken('API token of '. $user->name)->plainTextToken
        ]);



    }
    public function logout(Request $request){

        return response()->json('logged out');
    }
}
