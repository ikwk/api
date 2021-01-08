<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Post;
use File;
use Tymon\JWTAuth\facades\JWTAuth;

class PostsController extends Controller
{
    public function create(Request $request){

        $posts = new Post;
        $posts->user_id = 2;

        //Check If Post  
        if(!empty($request->file('file'))){
            $posts->file = $this->fileHandling($request, 'file');
        }
        
        $ch=curl_init("https://fcm.googleapis.com/fcm/send");
        $header=array("Content-Type:application/json", "Authorization: key=AAAAER3_ENw:APA91bGK5urdJYEuGiwOjJQqtmtRw4lMe8Ma6C-Vgy_DaDlZ88rKKA4Ag0hioumpnx0Kl4eZfpct8bKdzmafUiATEAFCl8DqKH0u8Ubxx0p7iZWcws4Ovn8ulewA8b04jTM6FJr-kFS5");
        $data=json_encode(array("to"=>"/topics/allDevices","data"=>array("title"=>"SKP App", "message"=>"SKP Baru Telah di Publish!")));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        //Mistake
        $is_success = $posts->save();

        if($is_success){
            return response()->json([
                'success' => true,
                'message' => 'file berhasil diupload'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'file gagal diupload'
        ]);

    }

    public function fileHandling(Request $req, $var){
        $file = $req->file($var);
        $filename = $file->getClientOriginalName();
        $extension = explode('.', $filename);
        $extension = end($extension);
        $filename = $this->generateRandomString().'.'.$extension;
        $file->move(public_path('uploads'), $filename);
        return $filename;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function update(Request $request){
        $posts = Post::find($request->id);
        if(!empty($request->file('file'))){
            $posts->file = $this->fileHandling($request, 'file');
        }
        $posts->update();
        return response()->json([
            'success' => true,
            'message' => 'Post edited',
            'Post'=> $posts
        ]);
    }
    
    public function delete(Request $request){
        $posts = Post::find($request->id);

        $posts->delete();
        return response()->json([
            'success' => true,
            'message' => 'Post deleted'
        ]);
    }
    
    public function posts(){
        $posts = Post::with(["user"])->orderBy('id','desc')->get();
        return response()->json([
            'success' => true,
            'post' => $posts
        ]);
    }

}
