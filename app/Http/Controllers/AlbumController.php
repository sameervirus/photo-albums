<?php

namespace App\Http\Controllers;

use App\DataTables\AlbumsDataTable;
use App\Models\Album;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AlbumsDataTable $dataTable)
    {
        return $dataTable->render('albums.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'min:4', 'max:255'],
            'names' => ['required'],
            'names.*' => ['required', 'min:4', 'max:255'],
            'images' => ['required'],
            'images.*' => ['mimes:png,jpg,jpeg', 'max:2048']
        ]);

        try {
            $album = Album::create([
                'title' => request('title'),
                'user_id' => auth()->user()->id
            ]);

            if ($request->hasFile('images')) {
                for ($i=0; $i < count(request('images')); $i++) {
                    $album->addMedia($request->file('images')[$i])
                            ->usingName(request('names')[$i])
                            ->toMediaCollection();
                }
            }
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('albums.index')->with('message','Album successfully uploaded');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        return view('albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $request->validate([
            'title' => ['required', 'min:4', 'max:255'],
            'names.*' => ['nullable', 'min:4', 'max:255'],
            'images.*' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048']
        ]);

        try {

            if($request->has('images_name')) {

                foreach(request('images_name') as $name)
                {
                    $id = array_keys($name)[0];
                    Media::where("id", $id)->update(['name' => $name[$id]]);
                }
            }

            $album->title = request('title');

            if($album->save()) {
                if ($request->hasFile('images')) {
                    for ($i=0; $i < count(request('images')); $i++) {
                        $album->addMedia($request->file('images')[$i])
                                ->usingName(request('names')[$i])
                                ->toMediaCollection();
                    }
                }
            }
        } catch (\Throwable $th) {
            return $th;
        }

        return redirect()->route('albums.index')->with('message','Album successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Album $album)
    {
        try {
            if(request('choice') == 2) {
                Media::where('model_id', $album->id)->update('model_id', request('album'));
            }

            $album->delete();
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
        return ['message' => 'success'];
    }
}
