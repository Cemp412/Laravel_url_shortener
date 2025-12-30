<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\{Auth, DB, Mail};
use App\Http\Requests\StoreinvitationRequest;
use App\Models\Invitation;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\InvitationMail;
use Validator;

class InvitationController extends Controller
{
    /**
     * Store a newly created invitation
     * Send invitation mail
     * 
     * @return Illuminate\Http\JsonResponse;
     */
    public function store(StoreinvitationRequest $request){
        try{
            $authUser = Auth::user();
        
            DB::beginTransaction();

            //Company creation logic for role('superadmin')
            if($authUser->hasRole('superadmin')) {
                $company = Company::create([
                    'name' => $request->name,
                    'email' => $request->email
                ]);
            }
            else{ 
                //Company creation logic for role('admin')
                $company = $authUser->company;
            }

            // Create Invitation
            $invitation = Invitation::create([
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $company->id,
                'role' => $request->role,
                'token' => Str::uuid(),
                'expires_at' => now()->addDays(3),
                'created_by' => $authUser->id,
                'is_active' => true
            ]);

            // if(isset($invitation)) {
                Mail::to($invitation->email)->send(new InvitationMail($invitation));
            // }

            DB::commit();
            return $this->sendResponse($invitation, 'Invitation sent successfully', 201);
        }
        catch(Exception $e){
            DB::rollback();

            return $this->sendError('Server error', [$e->getmessage()], 500);
        }
    }

    /**
     * accept invitation send via email
     * 
     * @return Illuminate\Http\JsonResponse;
     */
    public function acceptInvite(Request $request, $token = null) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:invitations,email',
            'password' => 'required|min:8|max:20'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        try{
            DB::beginTransaction();
        
            $invitation = Invitation::where($token) 
                            ->where('email', $request->input('email'))
                            ->whereNull('accepted_at')
                            ->where('expires_at', '>', now())
                            ->firstOrFail();

            $user = User::create([
                'name' => $invitation->name,
                'email' => $invitation->email,
                'company_id' => $invitation->company_id,
                'password' => hashWithPepper($request->input('password')),
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

            $success['email'] = $invitation->email;
            $success['redirect_to'] = route('login', ['email' => $invitation->email]);
            return $this->sendResponse($success, 'Invitation accepted. You can login in now!');
        }
        catch(Exception $e) {
            DB::rollback();
            return $this->sendError("Server Error", [$e->getmessage()], 500);
        }
    }
}
