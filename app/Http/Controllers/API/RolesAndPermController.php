<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\RoleResource;

class RolesAndPermController extends BaseController
{
    /**
     * roles list
     * 
     * @return Illuminate\Http\JsonResponse;
     */
    public function getRoles() {
        $roles = Role::whereNotIn('name', ['superadmin'])->get();
        $success['roles'] = RoleResource::collection($roles);
        return $this->sendResponse($success, 'Roles fetched successfully');

    }
}
