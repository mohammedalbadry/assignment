<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use MyResponseTrait;

    public function allroles()
    {
        $data = Role::all();
        return $this->mainResponse($data);
    }
    
    public function index()
    {
        $this->notHavePermission('role-read');
        $data = Role::paginate(15);
        return $this->mainResponse($data);
    }

    public function store(Request $request)
    {
        $this->notHavePermission('role-create');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles|min:2',
            'description' => 'nullable|string',
            'permissions' => 'required|array|min:1'
        ]);
        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        $role = Role::create([
            'name' => $request->name,
            'display_name' => ucwords(str_replace('_', ' ', $request->name)),
            'description' =>  $request->description == null ? ucwords(str_replace('_', ' ', $request->name)) : $request->description
        ]);
        $role->permissions()->sync(explode(",",$request->permissions[0]));
        return $this->createdResponse();
    }

    public function show($id)
    {
        $this->notHavePermission('role-read');
        $data = Role::with('permissions')->where('id', $id)->first();
        return $this->mainResponse($data);
    }

    public function update(Request $request, $id)
    {
        $this->notHavePermission('role-update');

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|string|unique:roles,name,'.$id,
            'description' => 'nullable|string',
            'permissions' => 'required|array|min:1'
        ]);
        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        $role =  Role::find($id)->update([
            'name' => $request->name,
            'display_name' => ucwords(str_replace('_', ' ', $request->name)),
            'description' =>  $request->description == null ? ucwords(str_replace('_', ' ', $request->name)) : $request->description
        ]);

        Role::find($id)->permissions()->sync(explode(",",$request->permissions[0]));
        return $this->updateResponse();
    }

    public function destroy($id)
    {
        $this->notHavePermission('role-delete');
        $role = Role::where('id',$id)->first();
        
        if(!$role){
            return $this->errorResponse(['error' => "item not found"]);
        } else{
            if(count($role->users) > 0){
                return $this->errorResponse(['error' => "This role is in use"]);
            } else {
                $role->delete();
                return $this->deleteResponse();
            }  
        }
    }
}
