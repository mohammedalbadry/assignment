<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;

class PermissionController extends Controller
{
    use MyResponseTrait;
    
    public function index()
    {
        $data = Permission::all();
        return $this->mainResponse($data);
    }
}
