<?php

namespace App\Http\Controllers;

use App\Http\Traits\myFileTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use MyResponseTrait,  myFileTrait;

    public function index(Request $request)
    {
        $this->notHavePermission('admins-read');
        $data = Admin::when($request->search, function($query) use ($request){
            return $query->where('name', 'like', '%' . $request->search . '%');
        })->where('id','!=', 1)->where('id','!=', auth()->user()->id)
        ->with('roles')->orderBy('id', 'DESC')->paginate(15);
        return $this->mainResponse($data);
    }

    public function store(Request $request)
    {
        $this->notHavePermission('admins-create');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:admins',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required',
            'role' => 'required',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        
        $admin = Admin::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $admin->attachRole($request->role);
        $admin->attachPermissions(Role::find($request->role)->permissions);
        return $this->createdResponse();

    }

    public function show($id)
    {
        $this->notHavePermission('admin-read');
        $data = Admin::with('roles')->where('id', $id)->first();
        return $this->mainResponse($data);
    }

    public function update(Request $request, $id)
    {
        $this->notHavePermission('admins-update');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:admins,email,'.$id,
            'password' => 'nullable|confirmed|min:6',
            'role' => 'required',
        ]);


        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        
        $data = $validator->validated();
        $model = Admin::find($id);

        if ($file = $request->file('photo')) { 
            $this->mydelete($model->photo, "admins/". $model->photo);   
            $data['photo'] =  $this->myupload($file, "/uploads/admins//");   
        }
        if ($request->password) { 
            $data['password'] = bcrypt($request->password); 
        }

        $model->update($data);
        $model->syncRoles([$request->role]);
        $model->syncPermissions(Role::find($request->role)->permissions);
        return $this->updateResponse();
    }


    public function destroy($id)
    {
        $this->notHavePermission('admin-delete');
        $admin = Admin::where('id',$id)->where('id','!=', 1)->where('id','!=', auth()->user()->id)->first();
        if(!$admin){
            return $this->errorResponse(['error' => "item not found"]);
        } else{
            $this->mydelete($admin->photo, "admins/". $admin->photo);   
            $admin->delete();
            return $this->deleteResponse();
        }
    }
}
