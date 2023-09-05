<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function allblog()
    {
        $allblogs = Blog::with('user')->paginate(env('PAGINATION_PER_PAGE'));
        return response()->json($allblogs, 200);
    }

    public function myblog()
    {
        $allblogs = Blog::where('user_id', auth()->user()->id)->with('user')->paginate(env('PAGINATION_PER_PAGE'));
        return response()->json($allblogs, 200);
    }

    public function store(Request $request)
    {
        if($request->get('id') == 0)
        {
            $validateBlog = Validator::make($request->all(), 
            [
                'title' => 'required|min:10',
                'description' => 'required|min:100'
            ]);

            if($validateBlog->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateBlog->messages()->all()
                ], 401);
            }

            $blog = Blog::create([
                'title' => $request->title,
                'description' => $request->description,
                'show_related' => $request->show_related,
                'user_id' => auth()->user()->id
            ]);

            if($request->image)
            {
                $extension = explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];   // .jpg .png .pdf

                $replace = substr($request->image, 0, strpos($request->image, ',')+1); 

                $image = str_replace($replace, '', $request->image); 

                $image = str_replace(' ', '+', $image); 

                $imageName = $blog->id.'.'.$extension;

                Storage::disk('public')->put($imageName, base64_decode($image));

                $blog->image = $imageName;
                $blog->update();
            }

            return response()->json([
                'status' => true,
                'message' => 'Blog Created'
            ], 200);
        }
        else
        {
            $validateBlog = Validator::make($request->all(), 
            [
                'title' => 'required|min:10',
                'description' => 'required|min:100'
            ]);

            if($validateBlog->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateBlog->messages()->all()
                ], 401);
            }

            $blog = Blog::where([
                    'id' => $request->get('id'),
                    'user_id' => auth()->user()->id
                ])->first();
            $blog->update([
                'title' => $request->title,
                'description' => $request->description,
                'show_related' => $request->show_related,
                'user_id' => auth()->user()->id
            ]);

            if($request->image)
            {
                $extension = explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];   // .jpg .png .pdf

                $replace = substr($request->image, 0, strpos($request->image, ',')+1); 

                $image = str_replace($replace, '', $request->image); 

                $image = str_replace(' ', '+', $image); 

                $imageName = $blog->id.'.'.$extension;

                Storage::disk('public')->put($imageName, base64_decode($image));

                $blog->image = $imageName;
                $blog->update();
            }

            return response()->json([
                'status' => true,
                'message' => 'Blog Updated'
            ], 200);



        }
    }

    public function edit($id)
    {
        $blog = Blog::where([
                    'id' => $id,
                    'user_id' => auth()->user()->id
                ])->first();
        if($blog)
        {
            return response()->json([
                'status' => true,
                'data' => $blog
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'No Authorized'
            ], 401);
        }
    }

    public function show($id)
    {
        $blog = Blog::where([
                    'id' => $id
                ])->with('user')->first();
        if($blog)
        {
            $relatedBlog = [];
            if($blog->show_related == true)
            {
                $relatedBlog = Blog::where('id', '!=', $id)->where('user_id', $blog->user_id)->with('user')->inRandomOrder()->limit(4)->get();
            }
            return response()->json([
                'status' => true,
                'data' => $blog,
                'related_blog' => $relatedBlog
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => false,
                'message' => 'No blog found'
            ], 401);
        }
    }

    public function search($keywords)
    {
        $allblogs = Blog::with('user')
                    ->where('title', 'LIKE', '%'.$keywords.'%')
                    ->paginate(env('PAGINATION_PER_PAGE'));

        return response()->json($allblogs, 200);
    }
}
