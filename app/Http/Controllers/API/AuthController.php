<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\password_helper;
use Validator;


class AuthController extends BaseController
{
    /**
     * 
     * Register API 
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function register(Request $request): Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|max:20|confirmed',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all(); 
        $input['password'] = hashWithPepper($request->password);
        $user = User::create($input);

        // Generate token
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully');
    }


    /**
     * Show login form
     */
    public function showLogin() {
        return view('auth.login');
    }


    /**
     * 
     * Login API
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:20',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
              
        $pepper = config('app.pepper');
        $input = [
            'email' => $request->input('email'),
            'password' => $request->input('password') . $pepper //hashWithPepper($request->password) //
        ];

        if(Auth::attempt($input)) {
            $user = Auth::user();
            $redirectTo = '/dashboard';
            //Generate token
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $success['redirect'] = session()->pull('url.intended', route('dashboard')); //$redirectTo;

            return $this->sendResponse($success, 'User login successfully');

        }
        else{
            return $this->sendError('Unauthorised.', ['error' => 'Invalid email or password.'], 401);
        }
    }

    /**
     * Logout API
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->sendResponse([], 'Successfully logged out');
    }
}
