<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use Illuminate\Support\Str;
use Auth;

use App\Models\Guide;

class GuideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guides = Guide::latest()->get();

        return view('guides.index', compact('guides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guides.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuideRequest $request)
    {
        $payload = $request->validated();
        $payload["user_id"] = Auth::user()->id;
        $payload["slug"] = Str::slug($payload["title"], '-')."-".rand(00000, 99999);

        Guide::create($payload);

        return redirect()->route('guides.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $guide = Guide::where('slug', $slug)->first();
        return view('guides.view', ['guide' => $guide]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guide $guide)
    {
        return view('guides.edit', compact('guide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGuideRequest $request, Guide $guide)
    {
        $guide->update($request->validated());

        return redirect()->route('guides.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guide $guide)
    {
        $guide->delete();

        return redirect()->route('guides.index');
    }

    public function upload(Request $request)
    {
        $guide = new Guide();
        $guide->id = 0;
        $guide->exists = true;
        $image = $guide->addMediaFromRequest('file')->toMediaCollection('preview');

        return response()->json([
            'url' => $image->getUrl('preview')
        ]);
    }
}
