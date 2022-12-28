<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Str;

class BlogController extends Controller
{
    public function index()
    {
        try {
            $role = Role::find(Auth::user()->role_id);
            if ($role->name == "User") {
                $blog = Blog::whereHas('user', function ($query) use ($role) {
                    $query->where('role_id', $role->id);
                })->paginate(10);
            } else {
                $blog = Blog::paginate(10);
            }

            return $this->responseJson(200, true, count($blog) != 0 ? 'List blog' : 'Blog is still empty', $blog);
        } catch (\Throwable $th) {
            return $this->responseJson(500, false, $th->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'content' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return $this->responseValidate(400, false, 'validation error', $validateUser->errors());
            }

            $blog = Blog::create([
                "user_id" => Auth::user()->id,
                "title" => $request->title,
                "slug" => Str::slug($request->title),
                "content" => $request->content
            ]);

            return $this->responseJson(201, true, 'Blog created', $blog);
        } catch (\Throwable $th) {
            return $this->responseJson(500, false, $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'content' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return $this->responseValidate(400, false, 'validation error', $validateUser->errors());
            }

            $blog = $this->checkRole($id);
            if ($blog) {
                $blog->update([
                    "title" => $request->title,
                    "slug" => Str::slug($request->title),
                    "content" => $request->content
                ]);
                return $this->responseJson(200, true, 'Blog updated', $blog);
            } else {
                return $this->responseJson(404, true, 'Blog not found');
            }
        } catch (\Throwable $th) {
            return $this->responseJson(500, false, $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $blog = $this->checkRole($id);
            if ($blog) {
                $blog->delete();
                return $this->responseJson(200, true, 'Blog deleted');
            } else {
                return $this->responseJson(404, true, 'Blog not found');
            }
        } catch (\Throwable $th) {
            return $this->responseJson(500, false, $th->getMessage());
        }
    }

    public function detail($id)
    {
        try {
            $blog = $this->checkRole($id);
            if ($blog) {
                return $this->responseJson(200, true, 'Blog available', $blog);
            } else {
                return $this->responseJson(404, true, 'Blog not found');
            }
        } catch (\Throwable $th) {
            return $this->responseJson(500, false, $th->getMessage());
        }
    }

    public function checkRole($blog)
    {
        $user = User::with('role')->find(Auth::user()->id);
        
        if ($user->role->name == "User") {
            $blog = Blog::where(['user_id' => $user->id, 'id' => $blog])->first();
            if ($blog) {
               return $blog;
            } else {
                return false;
            }
        } else {
            $blog = Blog::find($blog);
            if ($blog) {
               return $blog;
            } else {
                return false;
            }
        }

    }
}
