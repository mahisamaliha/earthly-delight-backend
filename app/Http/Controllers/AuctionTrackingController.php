<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuctionTracking;

use App\Models\Auction;
use Illuminate\Support\Facades\DB;

class AuctionTrackingController extends Controller
{
    /**
     * Store a newly created auction tracking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'auction_id' => 'required|exists:auctions,id',
                'bidding_price' => 'required|numeric|min:0',
            ]);

            // Create the auction tracking record
            $auctionTracking = AuctionTracking::create($validatedData);

            // Update the highest_bid field in the auctions table if the bidding price is greater than the current highest bid
            $auction = Auction::find($request->auction_id);
            if ($request->bidding_price > $auction->highest_bid) {
                $auction->highest_bid = $request->bidding_price;
                $auction->save();
            }

            // Return a success response
            return response()->json([
                'success' => true,
                'data' => $auctionTracking,
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e->getMessage());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Error creating auction tracking',
            ], 500);
        }
    }
    
    
    /**
     * Display the specified auction tracking.
     *
     * @param  \App\Models\AuctionTracking  $auctionTracking
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $auction_id)
    {
        // Retrieve the auction tracking record for the specified user_id and auction_id
        $auctionTracking = AuctionTracking::where('user_id', $user_id)
                                        ->where('auction_id', $auction_id)
                                        ->firstOrFail();
        
        // Return a success response
        return response()->json([
            'success' => true,
            'data' => $auctionTracking,
        ]);
    }

    
    /**
     * Update the specified auction tracking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AuctionTracking  $auctionTracking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId, $auctionId)
    {
        try {
            // Find the auction tracking record to update
            $auctionTracking = AuctionTracking::where('user_id', $userId)
                ->where('auction_id', $auctionId)
                ->firstOrFail();

            // Validate the request data
            $validatedData = $request->validate([
                'bidding_price' => 'required|numeric|min:0',
            ]);

            // Update the auction tracking record
            $auctionTracking->update($validatedData);

            // Update the highest_bid field in the auctions table if the bidding price is greater than the current highest bid
            $auction = Auction::find($auctionId);
            if ($request->bidding_price > $auction->highest_bid) {
                $auction->highest_bid = $request->bidding_price;
                $auction->save();
            }

            // Return a success response
            return response()->json([
                'success' => true,
                'data' => $auctionTracking,
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e->getMessage());

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Error updating auction tracking',
            ], 500);
        }
    }
    

    
    /**
     * Remove the specified auction tracking from storage.
     *
     * @param  \App\Models\AuctionTracking  $auctionTracking
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $auction_id)
    {
        // Find the auction tracking record by user_id and auction_id
        $auctionTracking = AuctionTracking::where('user_id', $user_id)->where('auction_id', $auction_id)->firstOrFail();
        
        // Delete the auction tracking record
        $auctionTracking->delete();
        
        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Auction tracking record has been deleted.',
        ]);
    }

    
    /**
     * Show the form for creating a new auction tracking.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Load the user and auction models
        $users = User::all();
        $auctions = Auction::all();
        
        // Return the create view
        return view('auction_tracking.create', compact('users', 'auctions'));
    }
}
