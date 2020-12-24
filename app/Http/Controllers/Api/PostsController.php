<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Post;
Use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{   
    public function create(Request $request)
    {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        
        // cek kalo post udah isi poto
        if ($request->photo != '') {
            $photo = time().'jpg';
            file_put_contents('storage/posts/'.$photo,base64_decode($request->photo));
            $photo->photo = $photo;
        }
        $post->save();
        $post->user; 
        return response()->json([
            'success' => true,
            'message' => 'posted',
            'post' => $post
        ]);
    }
    public function update(Request $request)
    {
        $post = Post::find($request->id);
        if (Auth::user()->id != $reuest->id) {
            return response()->json([
                'success' => false,
                'message' => 'tidak ada akses'
            ]);
            $post->update();
            return response()->json([
                'success' => true,
                'message' => 'post edited'
            ]);
        }
    }
    public function delete(Request $request)
    {
        $post = Post::find($request->id);
        if (Auth::user()->id != $request->id) {
            return response()->json([
                'success' => false,
                'message' => 'tidak ada akses'
            ]);
        }
        // cek jika post tersebut isi poto
        if ($post->photo !='') {
            Storage::delete('public/posts/'.$post->photo);
        }
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'post deleted'
        ]);
    }
}
