<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;


class TempImageController extends Controller
{
    public function store(Request $request){
        // apply validator
        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator ->errors()
            ]);
        }

        // upload image
        $image =$request ->image;
        $ext =$image->getClientOriginalExtension();
        $imageName = time().'.'.$ext;

        // store Image info database
        $tempImage = new TempImage();
        $tempImage->name = $imageName;
        $tempImage->save();

        // move image in temp dir
        $image->move(public_path('uploads/temp'), $imageName );
        return response()->json([
            'status' => true,
            'message' => 'Image uploaded succesfully',
            'image' => $tempImage
        ]);
    }

}
