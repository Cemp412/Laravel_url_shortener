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

}
