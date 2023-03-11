<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Accesorie;
use App\Models\Accesorie;


class AccesorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesories = Accesorie::all();
        return view('accesories.index', compact('accesories'));
    }
    public function checkIsWorking()
    {
        $data = Accesorie::get();
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accesories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $accesorie = new Accesorie;
        $accesorie->current_price = $request->current_price;
        $accesorie->highest_bid = $request->highest_bid;
        $accesorie->bid_count = $request->bid_count;
        $accesorie->time_remaining = $request->time_remaining;
        $accesorie->save();
        return redirect()->route('accesories.index')->with('success','Accesorie created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accesorie = Accesorie::findOrFail($id);
        return view('accesories.show', compact('accesorie'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accesorie = Accesorie::findOrFail($id);
        return view('accesories.edit', compact('accesorie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $accesorie = Accesorie::findOrFail($id);
        $accesorie->current_price = $request->current_price;
        $accesorie->highest_bid = $request->highest_bid;
        $accesorie->bid_count = $request->bid_count;
        $accesorie->time_remaining = $request->time_remaining;
        $accesorie->save();
    
        return redirect()->route('accesorie.index')->with('success', 'Accesorie updated successfully!');
    }

      /**
     * Delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accesorie = Accesorie::find($id);
        $accesorie->delete();

        return redirect()->route('accesorie.index')->with('success', 'Accesorie deleted successfully!');
    }
}