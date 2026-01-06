<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use App\Models\{Invitation, User, Company};
use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterInviteController extends Controller
{
    /**
     * Show register via invite if token is valid
     * 
     *  @return \Illuminate\View\View
     */
    public function show($token) {
        $invitation = Invitation::where('token', $token)
                        ->whereNull('accepted_at')
                        ->where('expires_at', '>', now())
                        ->first();

        if(!$invitation) {
            return redirect()->route('login')->with('error', 'Invitation invalid or expired.');
        }

        return view('invitations.register-invite', ['invitation' => $invitation]);
    }

    /**
     * accept invitation send via email
     * 
     * @return Illuminate\Http\JsonResponse;
     */
    public function store(Request $request, $token = null) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|exists:invitations,email|unique:users',
            'password' => 'required|string|min:8|max:20|confirmed'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();;
        }
        
        try{
            DB::beginTransaction();
        
            $invitation = Invitation::where('token', $token) 
                            ->where('email', $request->input('email'))
                            ->whereNull('accepted_at')
                            ->where('expires_at', '>', now())
                            ->first();

            if(!$invitation) {
                return redirect()->route('login')->with('error', 'Invitation invalid or expired.');
            }

            $user = User::create([
                'name' => strtolower($request->name),
                'email' => strtolower($invitation->email),
                'company_id' => $invitation->company_id,
                'password' => Hash::make($request->input('password')),
            ]);

            //assign role to user
            $user->assignRole($invitation->role);

            //set timestamp() value in invitations accepted_at
            $invitation->update([
                'accepted_at' => now(),
                'is_active' => false
            ]);

            //set company status to active
            $company = Company::where('id', $invitation->company_id)->update(['status' => 'active']);
            DB::commit();

            // $success['email'] = $invitation->email;
            // $success['redirect_to'] = route('login', ['email' => $invitation->email]);
            return redirect()->route('login', ['email' => $invitation->email])->with('success', 'Invitation accepted. You can login in now!');
        }
        catch(Exception $e) {
            DB::rollback();
            return back()->with("error", $e->getmessage());

        }
    }
}
