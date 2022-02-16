<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class Login extends Controller
{
    public function login(Request $request){
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ],[
                'email.required' => 'need email',
                'password.required' => 'need password',
    
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => Helpers::error_processor($validator),
                ]);
            }
            $user = User::where(['email' => $request->email])->first();
            if (isset($user)) {
                    $data = [
                    'email' => $user->email,
                    'password' => $request->password
                ];
                if (auth()->attempt($data)) {
                    Session::put('name',$user->name);
                    Session::put('user_id',$user->id);
                    return response()->json([
                        'status' => 'true',
                        'msg' => 'file-list',
                    ]);
                }
            }
            return response()->json([
                'status' => 'false',
                'msg' => 'some thing wrong ',
            ]);
      
    }
    public function login_view(){

        return view('login');
    }
}
