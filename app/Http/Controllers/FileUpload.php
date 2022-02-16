<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location_Model;
use App\Models\User;
use App\Models\Category_Model;

class FileUpload extends Controller
{

    public function __construct(Request $request)
    {
        // if($request->api_key==''||$request->api_key!='123'){
        //     return"no access";
        // }
    }
    public function upload(Request $request)
    {

        $zipcount=0;
        if( $request->category == ''){
            return response()->json(['status'=>'false', 'msg'=>'user category needed']);
        }
        if( $request->user_token == ''){
            return response()->json(['status'=>'false', 'msg'=>'user tocken needed']);
        }
        $user = User::where('tocken','=',$request->user_token )->first();

        if(!$user){
            return response()->json(['status'=>'false', 'msg'=>'user not found']);
        }

        $cat = Category_Model::where('id','=',$request->category )->first();
        if(!$cat){
            return response()->json(['status'=>'false', 'msg'=>'category not found']);
        }

        $file=$request->file('file');
        $file_name=$user->name.'_'.date('Y-m-d-H-i-s').'.'.$file->getClientOriginalExtension();
        $file_size=$request->file('file')->getSize();
        $file_size = number_format($file_size / 1048576,2);
        
        $zip = new \ZipArchive();
        $zip->open($file);
        //looped through the zip files and got each index name of the files
        for( $i = 0; $i < $zip->numFiles; $i++ ){
            $zipcount++;
            $array_explode=array();
            $array_explode=null;
            $filename = $zip->getNameIndex($i);
        
            $array_explode = explode('.', $filename);
            $filetype=$array_explode[count($array_explode) - 1];
            if( $filetype!="jpg" && $filetype!="jpeg" && $filetype!="png")
            {
                return response()->json(['status'=>'false', 'msg'=>'Not allowd file is in side the zip file','filename'=>$filename,'file_type'=>$filetype]);
            }
            $array_explode=null;
        }

        if($zipcount==0){
            return response()->json(['status'=>'false', 'msg'=>'not a zip file']);
        }
        if($file_size > 15){
            return response()->json(['status'=>'false', 'msg'=>'exeed the minimum file size']);
        }
        $result= $request->file('file')->storeAs('image_files',$file_name);
        if($result){

            $location = new Location_Model;
            $location->location_name = $result;
            $location->user = $user->tocken;
            $location->category = $cat->id;
            if($result && $location->save())
            {
                return response()->json(['status'=>'true', 'msg'=>'file uploaded']);               
            }else{
                return response()->json(['status'=>'false', 'msg'=>'file did not uploaded']);
            }
        }else{
            return response()->json(['status'=>'false', 'msg'=>'file did not uploaded']);
        }
    }
}
