<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Traits\MyResponseTrait;
use Illuminate\Support\Facades\Validator;

class TimetableController extends Controller
{
    use MyResponseTrait;
  
    public function index(Request $request)
    {
        $this->notHavePermission('timetable-read');

        $data = Timetable::when($request->search, function($query) use ($request){
            return $query->where('title', 'like', '%' . $request->search . '%');
        })->with('user','pharmacy')->orderBy('id', 'DESC')->paginate(15);
        return $this->mainResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->notHavePermission('timetable-create');

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,100',
            'user_id' => 'required|integer',
            'pharmacy_id' => 'required|integer',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time|before_or_equal:'. Carbon::parse($request->start_time)->addHours(24),
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }

        Timetable::create($validator->validated());
        return $this->createdResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->notHavePermission("timetable-read");
        $data = Timetable::with('user','pharmacy')->where('id', $id)->first();
        return $this->mainResponse($data);
    }

    public function update(Request $request, $id)
    {
        $this->notHavePermission('timetable-create');

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,100',
            'user_id' => 'required|integer',
            'pharmacy_id' => 'required|integer',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time|before_or_equal:'. Carbon::parse($request->start_time)->addHours(24),
        ]);

        if($validator->fails()){
            return $this->errorResponse( ['errors' => $validator->errors()]);
        }
        $item = Timetable::find($id);
        if(Carbon::parse($item->start_time) <= Carbon::now() || Carbon::parse($item->end_time) <= Carbon::now()){
            return $this->errorResponse([
                'errors' => [
                    'prevent' => "You cannot update a previous event"
                  ]
                ]);
        }
        $item->update($validator->validated());
        return $this->updateResponse();
    }

    public function destroy($id)
    {
        $this->notHavePermission('timetable-delete');
        $timetable = Timetable::where('id',$id)->first();
        if(!$timetable){
            return $this->errorResponse(['error' => "item not found"]);
        } else{
            if(Carbon::parse($timetable->start_time) <= Carbon::now() || Carbon::parse($timetable->end_time) <= Carbon::now()){
                return $this->errorResponse([
                    'errors' => [
                        'prevent' => "You cannot delete a previous event"
                      ]
                    ]);
            }
            $timetable->delete();
            return $this->deleteResponse();
        }
    }
}
