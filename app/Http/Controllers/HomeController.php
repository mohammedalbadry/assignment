<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Pharmacy;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;

class HomeController extends Controller
{

    use MyResponseTrait;

    public function index()
    {
        $pharmacies_count = Pharmacy::count();
        $users_count = User::count();
        $timetables_count = Timetable::count();
        $admins_count = Admin::count();
        
        return $this->mainResponse([
            'pharmacies_count' => $pharmacies_count,
            'users_count' => $users_count,
            'timetables_count' => $timetables_count,
            'admins_count' => $admins_count,
        ]);
    }
}
