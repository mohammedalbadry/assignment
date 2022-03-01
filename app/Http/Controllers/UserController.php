<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\myFileTrait;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use MyResponseTrait, myFileTrait;
 
    public function index(Request $request)
    {
        $this->notHavePermission('users-read');
        $data = User::when($request->search, function($query) use ($request){
            return $query->where('name', 'like', '%' . $request->search . '%');
        })->orderBy('id', 'DESC')->paginate(15);
        return $this->mainResponse($data);
    }

    public function store(Request $request)
    {
        $this->notHavePermission('users-create');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }

        $data = array_merge($validator->validated(),['password' => bcrypt($request->password)]);

        if ($file = $request->file('photo')) { 
            $data['photo'] =  $this->myupload($file, "/uploads/users/");   
        }
         
        User::create($data);

        return $this->createdResponse();
    }

    public function show($id)
    {
        $this->notHavePermission('users-read');
        $data = User::with('timetabls')->where('id', $id)->first();
        return $this->mainResponse($data);
    }

    public function update(Request $request, $id)
    {
        $this->notHavePermission('users-update');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|confirmed|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        $data = array_merge($validator->validated(),['password' => bcrypt($request->password)]);
        $model = User::find($id);

        if ($file = $request->file('photo')) { 
            $this->mydelete($model->photo, "users/". $model->photo);   
            $data['photo'] =  $this->myupload($file, "/uploads/users//");   
        }

        $model->update($data);
        return $this->updateResponse();
    }

    public function destroy($id)
    {
        $this->notHavePermission('users-delete');
        $user = User::where('id',$id)->first();
        if(!$user){
            return $this->errorResponse(['error' => "item not found"]);
        } else{
            $this->mydelete($user->photo, "users/". $user->photo);   
            $user->delete();
            return $this->deleteResponse();
        }
    }
}
