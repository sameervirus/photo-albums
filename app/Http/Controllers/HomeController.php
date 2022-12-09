<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function deleteimg(Request $request)
    {
        $media = Media::find($request->input('id'));
        $model = Album::find($media->model_id);
                $model->deleteMedia($media->id);
        try {
            if($media->delete()) {
                return $media->name;
            } else {
                return response()->json(['status' => 'error', 'message' => 'couldn\'t delete'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
