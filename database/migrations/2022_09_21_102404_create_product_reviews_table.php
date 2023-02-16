<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('rating');
            $table->string('description');
            $table->timestamps();
        });
//         class Rating extends Model {
//             public $timestamps = false;
//             }
            
//             // Insert dummy models
//             for ($i=1; $i <= 30; $i++) {
//               Rating::forceCreate([
//                   'rating' => rand(1,5),
//               ]);
//             }
            
//             // Retrieve models
//             $ratings = Rating::select('rating', DB::raw('count(id) as amount'))
//                 ->groupBy('rating')
//                 ->get();
            
            
//             $total = $ratings->sum('amount');
            
//             $out = compact('ratings', 'total');
    
// }
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_reviews');
    }
}
