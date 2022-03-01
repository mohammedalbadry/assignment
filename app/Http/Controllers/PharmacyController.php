<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
{
    use MyResponseTrait;
 
    public function index(Request $request)
    {
        $this->notHavePermission('pharmacies-read');

        $data = Pharmacy::when($request->search, function($query) use ($request){
            return $query->where('name', 'like', '%' . $request->search . '%');
        })->orderBy('id', 'DESC')->paginate(15);
        return $this->mainResponse($data);
    }

    public function store(Request $request)
    {
        $this->notHavePermission('pharmacies-create');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
   
        Pharmacy::create($validator->validated());
        return $this->createdResponse();
    }


    public function show($id)
    {
        $this->notHavePermission('pharmacies-read');
        $data = Pharmacy::with('timetabls')->where('id', $id)->first();
        return $this->mainResponse($data);
    }

    public function update(Request $request, $id)
    {
        $this->notHavePermission('pharmacies-update');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
   
        Pharmacy::find($id)->update($validator->validated());
        return $this->updateResponse();
    }

    public function destroy($id)
    {
        $this->notHavePermission('pharmacies-delete');

        $pharmacy = Pharmacy::where('id',$id)->first();
        if(!$pharmacy){
            return $this->errorResponse(['error' => "item not found"]);
        } else{
            $pharmacy->delete();
            return $this->deleteResponse();
        }
    }
}
