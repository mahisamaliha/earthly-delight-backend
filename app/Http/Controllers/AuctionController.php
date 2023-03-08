<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Auction;
use App\Models\Auction;


class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auctions = Auction::all();
        return view('auctions.index', compact('auctions'));
    }
    public function checkIsWorking()
    {
        $data = Auction::get();
        
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
        return view('auctions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auction = new Auction;
        $auction->current_price = $request->current_price;
        $auction->highest_bid = $request->highest_bid;
        $auction->bid_count = $request->bid_count;
        $auction->time_remaining = $request->time_remaining;
        $auction->save();
        return redirect()->route('auctions.index')->with('success','Auction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auction = Auction::findOrFail($id);
        return view('auctions.show', compact('auction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auction = Auction::findOrFail($id);
        return view('auctions.edit', compact('auction'));
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
        $auction = Auction::findOrFail($id);
        $auction->current_price = $request->current_price;
        $auction->highest_bid = $request->highest_bid;
        $auction->bid_count = $request->bid_count;
        $auction->time_remaining = $request->time_remaining;
        $auction->save();
    
        return redirect()->route('auction.index')->with('success', 'Auction updated successfully!');
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
        $auction = Auction::find($id);
        $auction->delete();

        return redirect()->route('auction.index')->with('success', 'Auction deleted successfully!');
    }
}