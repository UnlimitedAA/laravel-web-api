<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Attraction as AttractionResource;
use App\Attraction;

class AttractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AttractionResource::collection(Attraction::with('city')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:attractions|max:255',
            'city_id' => 'required|numeric|min:1|exists:App\City,id',
        ]);

        $attraction = Attraction::create($validatedData);
        return new AttractionResource($attraction);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attraction $attraction)
    {
        return new AttractionResource($attraction->load('city'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attraction $attraction)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:cities|max:255',
            'city_id' => 'required|numeric|min:1|exists:App\City,id',
        ]);

        $attraction->update($validatedData);

        return new AttractionResource($attraction->load('city'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attraction $attraction)
    {
        $attraction->delete();

        return response()->json(null, 204);
    }
}
