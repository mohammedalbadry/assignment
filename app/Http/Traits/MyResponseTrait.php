<?php
namespace App\Http\Traits;

trait MyResponseTrait{

    private function HandleResponseJson($data, $status, $statusText, $message){
        return response()->json([
            'data' => $data,
            'status' => $status,
            'statusText' => $statusText,
            'message' => $message,
        ]);
    }
    
    protected function createdResponse($data = null){
        return $this->HandleResponseJson( $data, 200, "created", "created successfully" );
    }

    protected function sentResponse($data = null){
        return $this->HandleResponseJson( $data, 200, "sent", "sent succesfully" );
    }

    protected function updateResponse($data = null){
        return $this->HandleResponseJson( $data, 200, "updated", "updated successfully" );
    }

    protected function deleteResponse($data = null){
        return $this->HandleResponseJson( $data, 200, "deleted", "deleted successfully" );
    }

    protected function mainResponse($data){
        return $this->HandleResponseJson( $data, 200, "success", "main response" );
    }

    protected function errorResponse($data){
        return $this->HandleResponseJson( $data, 404, "error", "something went wrong" );
    }

    public function notHavePermission($permissions){
        if(!auth()->user("admin")->isAbleTo($permissions) ){
            return $this->forbiddenResponse();
        }
    }

    protected function forbiddenResponse($data = null){
        return $this->HandleResponseJson( $data, 403, "forbidden", "You are prohibited from accessing this feature" );
    }
    
}

