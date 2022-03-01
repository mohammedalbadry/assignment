<?php
namespace App\Http\Traits;
use Image;
use Illuminate\Support\Facades\Storage;

trait myFileTrait{

    public function mydelete($item, $path){
        if($item != "default.png") {      
            Storage::disk('public_uploads')->delete( $path );
        }
    }
    
    public function myupload($item, $path){
        $fileUpload = Image::make($item);
        $image_name = time() . "." . $item->getClientOriginalExtension();
        $fileUpload->save(public_path($path) . $image_name);
        return $image_name;
    }

    
}

