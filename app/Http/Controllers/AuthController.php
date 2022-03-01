<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\UserResetPassword;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use MyResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'ForgotPassword', 'ResetPassword']]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required ',
        ]);
        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        
        User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->errorResponse(['error'=>'Unauthorized']);
        }

        return $this->respondWithToken($token);
    }
    
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->errorResponse(['error'=>'Unauthorized']);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
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
        //get user
        $user = User::where('email', $request->email)->first();
        if(!empty($user)){
            //create token
            $token = app('auth.password.broker')->createToken($user);
            //insert data
            $data = DB::table('password_resets')->insert([
                'email' => $user->email,
                'token'=> $token,
                'created_at'=>Carbon::now()    
            ]);
            mail::to($user->email)->send(new UserResetPassword(['data'=>$user,'token'=>$token]));
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
            $user = User::where('email', $token->email)
            ->update(['email' => $token->email, 'password' => bcrypt(request('password'))]);

            DB::table('password_resets')->where('email', request('email'))->delete();

            $credentials = [
                'email' => $token->email,
                'password' => $request['password'],
            ];

            if (! $token = auth()->attempt($credentials)) {
                return $this->errorResponse(['error'=>'Unauthorized']);
            }

            return $this->respondWithToken($token);

        } else {
            return $this->errorResponse(['error'=>"the email are incorrect"]);
        }
    }

}
