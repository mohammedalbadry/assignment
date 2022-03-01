<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Mail\AdminResetPassword;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    use MyResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'ForgotPassword', 'ResetPassword']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('admin')->attempt($credentials)) {
            return $this->errorResponse(['error'=>'Unauthorized']);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        $admin = auth("admin")->user();
        $roles = $admin->roles;
        $permissions  = $admin->permissions;
        return response()->json([
            'user' => $admin,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function logout()
    {
        auth("admin")->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function ForgotPassword(Request $request){
        //get admin
        $admin = Admin::where('email', $request->email)->first();
        if(!empty($admin)){
            //create token
            $token = app('auth.password.broker')->createToken($admin);
            //insert data
            $data = DB::table('password_resets')->insert([
                'email' => $admin->email,
                'token'=> $token,
                'created_at'=>Carbon::now()    
            ]);
            mail::to($admin->email)->send(new AdminResetPassword(['data'=>$admin,'token'=>$token]));
            return $this->sentResponse();
        } else {
            return $this->errorResponse(['error'=>"the email are incorrect"]);
        }
    }

    public function ResetPassword(Request $request, $token) 
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6',
        ]);
        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }

        $token = DB::table('password_resets')->where('token', $token)
                                             ->where('created_at', '>' ,Carbon::now()->subHours(2))->first();

        if(!empty($token)) {
            $admin = Admin::where('email', $token->email)
            ->update(['email' => $token->email, 'password' => bcrypt(request('password'))]);

            DB::table('password_resets')->where('email', request('email'))->delete();

            $credentials = [
                'email' => $token->email,
                'password' => $request['password'],
            ];

            if (! $token = auth('admin')->attempt($credentials)) {
                return $this->errorResponse(['error'=>'Unauthorized']);
            }

            return $this->respondWithToken($token);

        } else {
            return $this->errorResponse(['error'=>"the email are incorrect"]);
        }
    }

}
