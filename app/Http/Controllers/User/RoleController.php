<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RoleCreateRequest;
use App\Http\Requests\User\RoleUpdateRequest;
use App\Http\Resources\Common\RoleResource;
use App\Http\Services\User\RolesService;
use App\Models\Common\Role;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {

        return RoleResource::collection(Role::all());
    }

    public function show(Role $role)
    {

        if (is_null($role)) {
            return $this->SendMessage("Role is incorrect or Not Exisit.", 404);
        }
        return new RoleResource($role);
    }

    public function store(RoleCreateRequest $request)
    {

        $created_role = RolesService::CreateRole(
            $request->name,
        );
        $data = new RoleResource($created_role);
        return $this->SendResponse("Role Created.", $data, 201);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {

        // Check if the role exists
        if (is_null($role)) {
            return $this->SendMessage("Role is incorrect or Not Exisit.", 404);
        }

        $Updated_role = RolesService::UpdateRole($role, $request);
        $data = new RoleResource($Updated_role);

        return $this->SendResponse("Role Updated.", $data, 200);
    }

    public function destroy(Role $role)
    {
        if (is_null($role)) {
            return $this->SendMessage("Role is incorrect or Not Exisit.", 404);
        }

        RolesService::DeleteRole($role);
        return $this->SendMessage("Role Deleted.", 200);
    }
}
