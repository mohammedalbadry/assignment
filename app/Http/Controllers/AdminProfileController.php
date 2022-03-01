<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Traits\myFileTrait;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    use MyResponseTrait,  myFileTrait;

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:admins,email,'.auth("admin")->user()->id,
            'password' => 'nullable|confirmed|min:6',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        
        $data = $validator->validated();
        $model = Admin::find(auth("admin")->user()->id);

        if ($file = $request->file('photo')) { 
            $this->mydelete($model->photo, "admins/". $model->photo);   
            $data['photo'] =  $this->myupload($file, "/uploads/admins//");   
        }
        if ($request->password) { 
            $data['password'] = bcrypt($request->password); 
        }

        $model->update($data);
        return $this->updateResponse();
    }
}
