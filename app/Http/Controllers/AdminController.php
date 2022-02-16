<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location_Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ImageResize;
use App\Image;

class AdminController extends Controller
{
    public function show_list(){
        $locations = Location_Model::paginate(2);
        return view('file_list',['locations'=>$locations]);
    }

    
    public function edit_images(){
        $locations = Location_Model::where('status','=',1)->paginate(2);
        return view('edit_image',['locations'=>$locations]);
    }
    
    public function get_images(Request $request){
        $locations = Location_Model::find($request->id);
            $path = Storage::path($locations->location_name);

            $zip = new \ZipArchive();
            $res = $zip->open($path);
            if ($res === TRUE) {
              $zip->extractTo(storage_path('app/admin_images/'.$request->id));
              $zip->close();

              ///file resize
              $path = storage_path('app/admin_images/'.$request->id);
              $imagepath=url("storage/app/admin_images").'/'.$request->id;
              $html='';
                $files = File::allFiles($path);
                foreach($files as $paths) { 
                
                
                $file = pathinfo($paths);
                
                    $filetosend=$path.'/'.$file["filename"].'.'.$file["extension"];
                    $file_demention=getimagesize($filetosend);
                    $width = $file_demention[0];
                    $height = $file_demention[1];

                    $ratio_orig = $width/200 ;

                    if ($width/$height > $ratio_orig) {
                        $width = $height*$ratio_orig;
                     } else {
                        $height = $width/$ratio_orig;
                     }
                    // var_dump( $height);
                    // die();
                    $img                     =       ImageResize::make($filetosend);
        
        
                    // --------- [ Resize Image ] ---------------
            
                    $img->resize($width , 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path.'/'.$file["filename"].'.'.$file["extension"]);
                    }

              ///end
              
              Location_Model::where("id",$request->id )->update(["extract" => 1]);
              return response()->json(['status'=>'true', 'msg'=>'extracted']);  
            } else {
              
              return response()->json(['status'=>'false', 'msg'=>'did not extracted']); 
            }
        // return view('file_list',['locations'=>$locations]);
    }
    public function remove_file(Request $request){

        $deleted=File::deleteDirectory(storage_path('app/admin_images/'.$request->id));
        if($deleted){
            Location_Model::where("id",$request->id )->update(["extract" => 0]);
            return response()->json(['status'=>'true', 'msg'=>'deleted']); 
            
        }else{

            return response()->json(['status'=>'false', 'msg'=>'not deleted']); 

        }
    }

    public function show_images(Request $request)
    {
        $count=0;
        // $files = Storage::disk('app')->allFiles();
        $path = storage_path('app/admin_images/'.$request->id);
        $imagepath=url("storage/app/admin_images").'/'.$request->id;
        $html='';
      $files = File::allFiles($path);
      foreach($files as $paths) { 
        $count++;
        
        $file = pathinfo($paths);
        
            $filetosend=$path.'/'.$file["filename"].'.'.$file["extension"];
            $file_demention=getimagesize($filetosend);
           
        $html .='<div class="col-md-6 mt-2"><img class="imagecard" onclick="edit('.$count.')" id="image_card'.$count.'" src="'.$imagepath.'/'.$file["filename"].'.'.$file["extension"].'" style="height:200px;width:100%"></div>';

    } 
    
    // return $html;
    return response()->json(['status'=>'true', 'result'=>$html]); 



    }

    public function approve(Request $request){
        $updated=Location_Model::where("id",$request->id )->update(["status" => 1]);
        if($updated){
            return response()->json(['status'=>'true', 'msg'=>'file approved']); 
        }else{
            return response()->json(['status'=>'false', 'msg'=>'something whent wronng']); 

        }
    }

    
    public function imagefiles(Request $request)
    {
        $count=0;
        $path = storage_path('app/admin_images/'.$request->id);
        $imagepath=url("storage/app/admin_images").'/'.$request->id;
        $html='';
      $files = File::allFiles($path);
      foreach($files as $paths) { 
          $count++;
        $file = pathinfo($paths);
        $html .='<div class="col-md-6 mt-2" onclick="edit('.$count.')" id="image_card'.$count.'" >dsfsdf<img  src="'.$imagepath.'/'.$file["filename"].'.'.$file["extension"].'" style="height:200px;width:100%"></div>';

    } 
    
    // return $html;
    return response()->json(['status'=>'true', 'result'=>$html]); 



    }

    function resizeImage($SrcImage,$DestImage,$MaxWidth,$MaxHeight, $Quality)
    {
        list($iWidth,$iHeight,$type)    = getimagesize($SrcImage);
    
        //if you dont want to rescale image
    
        $NewWidth=$MaxWidth;
        $NewHeight=$MaxHeight;
        $NewCanves              = imagecreatetruecolor($NewWidth, $NewHeight);
    
        // Resize Image
        if(imagecopyresampled($NewCanves, $NewImage,0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight))
         {
            // copy file
            if(imagejpeg($NewCanves,$DestImage,$Quality))
            {
                imagedestroy($NewCanves);
                return true;
            }
        }
    }
}
