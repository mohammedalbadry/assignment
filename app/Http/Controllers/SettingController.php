<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Traits\myFileTrait;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use MyResponseTrait, myFileTrait;

    public function index()
    {
        $data = Settings::first();
        return $this->mainResponse($data);
    }
    
    public function update(Request $request)
    {
        $this->notHavePermission('settings-update');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'status' => 'required|string|min:2',
            'alt_text' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }

        $data = $validator->validated();
        $model = Settings::first();
        
        if ($file = $request->file('logo')) { 
            $this->mydelete($model->logo, "settings/". $model->logo);   
            $data['logo'] =  $this->myupload($file, "/uploads/settings//");   
        }

        if ($file = $request->file('icon')) { 
            $this->mydelete($model->icon, "settings/". $model->icon);   
            $data['icon'] =  $this->myupload($file, "/uploads/settings//");
        }

        $model->update($data);
        return $this->updateResponse();
    }

}
