<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    //this method will return all blogs
    public function index(){
        $blogs = Blog::orderBy('created_at','DESC')->get();

        return response()->json([
            'status' => true,
            'data' =>$blogs
        ]);
    }
    // this method will return a single blog
    public function show(){
        
    }
    // this method will return a store a blog
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:10',
            'author' => 'required|min:10'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator ->errors()
            ]);
        }

        $blog = new Blog();
        $blog->title =$request->title;
        $blog->author =$request->author;
        $blog->description =$request->description;
        $blog->shortDesc =$request->shortDesc;
        $blog->save();
        // belum mengatur image

        // save image here
        $tempImage = TempImage::find($request->image_id); //diambil dari frontend
        if($tempImage != null){
            // 1736318966.png
            $imageExtArray = explode('.',$tempImage->name);
            $ext = last($imageExtArray);
            $imageName = time().'-'.$blog->id.'.'.$ext;

            $blog->image = $imageName;
            $blog->save();  

            $sourcePath = public_path('uploads/temp/'.$tempImage->name);
            $destPath = public_path('uploads/blogs/'.$imageName);

            File::copy($sourcePath,$destPath);
        }

        return response()->json([
            'status' => true,
            'message' => 'Blog added succesfully',
            'data' => $blog
        ]);
        
        // atur postman prtama di api routes
        
    }
    // thiss method will update a blog
    public function update(){
        
    }

    // thiss method will delete a blog
    public function destroy(){
            
    }
}
