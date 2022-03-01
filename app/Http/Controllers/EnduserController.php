<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class EnduserController extends Controller
{

    use MyResponseTrait;
    
    public function gettimetable(Request $request)
    {

        $search = $request->search;
        $filter_start = $request->filter_start;
        $filter_end = $request->filter_end;
        
        $data = Timetable::whereHas('pharmacy', function($query) use ($search, $filter_start, $filter_end) {
            $query->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
            $query->when($filter_start, function ($q) use ($filter_start) {
                $q->whereDate('start_time','>=',$filter_start);
            });
            $query->when($filter_end, function ($q) use ($filter_end) {
                $q->whereDate('end_time','<=', $filter_end);
            });
        })->where('user_id', auth()->user()->id )
            ->with('pharmacy')->orderBy('id', 'DESC')
            ->get();

        return $this->mainResponse($data);

    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.auth()->user()->id,
            'password' => 'nullable|confirmed|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        $data = array_merge($validator->validated(),['password' => bcrypt($request->password)]);
        $model = User::find(auth()->user()->id);
        
        $model->update($data);
        return $this->updateResponse();
    }
    
}
