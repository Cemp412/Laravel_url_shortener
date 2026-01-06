<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\{Auth, DB, Mail, Log};
use App\Http\Requests\StoreinvitationRequest;
use App\Models\Invitation;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\InvitationMail;
use Validator;

class InvitationController extends BaseController
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
            $invitation = null;

            DB::beginTransaction();

            //Company creation logic for role('superadmin')
            // if($authUser->hasRole('superadmin')) {
                $company = $authUser->hasRole('superadmin') ? Company::create(['name' => $request->name,'email' => $request->email]) : $company = $authUser->company;
            // }
            // else{ 
                //Company creation logic for role('admin')
            //     $company = $authUser->company;
            // }
            if(!$company) throw new Exception("Company does not exist");

            // Create Invitation
            $invitation = Invitation::create([
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $company->id,
                'role' => $request->role ?? 'admin',
                'token' => Str::uuid(),
                'expires_at' => now()->addDays(3),
                'created_by' => $authUser->id,
                'is_active' => true
            ]);

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            Log::error("Invitation creation failed: " . $e->getMessage());
            return $this->sendError('Server error', [$e->getmessage()], 500);
        }

        try{
            Mail::to($invitation->email)->send(new InvitationMail($invitation));
        }
        catch(\Exception $e) {
            Log::warning("Invitation created (ID: {$invitation->id}) but mail failed: " . $e->getMessage());
            return $this->sendResponse( $invitation,  'Invitation created, but the notification email could not be sent. Please check your mail settings.', 201);
        }

        return $this->sendResponse($invitation, 'Invitation sent successfully', 201);

    }

}
