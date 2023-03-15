<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Product;
use App\Models\MainProduct;
use App\Models\Store;
use App\Models\Payment;
use App\Models\Paymentsheet;
use App\Models\Bonus;
use App\Models\Invoice;
use App\Models\Noti;
use App\Models\Cart;
use App\Models\Selling;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\User;
use App\Models\GiftVoucher;
use Auth;
use DB;
class OrderController extends Controller
{
    //
    public function contactNumber (){
        return "09678120120";
    }
    public function checkProductStore($cartItems){
        // return $cartItems;
        foreach($cartItems as $cartItem){
            $product = Product::where('id',$cartItem['productId'])->where('stock','>=',$cartItem['quantity'])->count();
            if($product == 0){
                return response()->json([
                    'next'=>false,
                    'message'=>$cartItem['mproduct']['productName']." product quantity is stock out!"
                ]);
            }
        }
        return response()->json([
            'next' => true,
            'message' => null
        ]);
    }

    public function storeOrder(Request $request){
        $data = $request->all();
        // return $data['billingZone'];
        $zoneId = $data['zoneId'];
        $cartItems= $data['cartItems'];
        $date = $data['date'];
        foreach($cartItems as $d){
            $product = Product::where('id',$d['productId'])->where('stock','>=',$d['quantity'])->count();
            if($product == 0){
                return response()->json([
                    'next'=>false,
                    'message'=>$d['mproduct']['productName']." product quantity is stock out!"
                ],422);
            }
        }
        // return $data;
        $user_id = Auth::user()->id;
        Customer::where('userId',$user_id)->update([
            'address'=> $data['billingAddress'],
            'postCode' => $data['postCode'],
            // city: data.billingCity,
            'zone' => $data['billingZone'],
            'customerName'=> $data['name'],
            'zoneId'=> $data['zoneId'],
            'cityId'=> $data['cityId'],
            'areaId'=> $data['areaId'],
        ]);
        unset($data['zoneId']);
        unset($data['cityId']);
        unset($data['areaId']);
        $data['userId'] = Auth::user()->id;
        // $data['billingZone'] = $data['billingZone'];
        // return $data['billingAddress'];
        $order = Order::create($data);
        // return $order;
        if($data['giftVoucherAmount'] > 0){
            GiftVoucher::where('code',$data['giftVoucherCode'])->delete();
        }
        Customer::where('userId',$user_id)->update([
            'address' => $data['billingAddress'],
            'postCode'=> $data['postCode'],
            'zone' => $data['billingCity'],
            'customerName' => $data['name'],
            'zoneId'=>$zoneId
        ]);
        User::where('id',$user_id)->update([
            'name'=> $data['name']
        ]);
        $Items =[];
        $totalQuantity = 0;
        foreach($cartItems as $cartItem){
            $ob = [
                'orderId' => $order['id'],
                'productId' =>  $cartItem['productId'],
                'quantity' => $cartItem['quantity'],
                'sellingPrice' => $cartItem['mproduct']['sellingPrice'],
                'price'=>0
            ];
            $totalQuantity += $cartItem['quantity'];
            if($cartItem['mproduct']['discount'] > 0) {
                $ob['price'] = ($cartItem['mproduct']['sellingPrice']) - (($cartItem['mproduct']['sellingPrice']) *$cartItem['mproduct']['discount'])/100;
            }else {
                $ob['price'] = $cartItem['mproduct']['sellingPrice'];
            }
            $ob['price'] = floor($ob['price']);
            array_push($Items,$ob);
        }
        $noti = Noti::create([
            'userId' => 0,
            'orderId'=>$order['id'],
            'title' => "Order #PX".$order['id']."is Order Placed",
            'msg' => "Order #PX".$order['id']."is Order Placed",
            'type' => "order",
            'seen' =>0
        ]);

        $order_details = OrderDetail::insert($Items);
        Cart::where('id',Auth::user()->id)->delete();
        $ssldata = [];
        $bkash_data = [];
        if($data['paymentType'] == "sslcommerz"){
            $ssldata = $this->sslPayment($order,$user_id);

              return  Order::where('id',$order['id'])->update([
                'sessionkey' => $ssldata['sessionkey']
                ]);
        }
        else if($data['paymentType'] == "Bkash"){
            $all_bkash_data = $this.payWithBkash($order,$user_id);

            return Order::where('id',$order['id']).update([
                'sessionkey'=> $all_bkash_data->paymentIntent->paymentID,
                'bkashJson'=> json_encode($all_bkash_data)
            ]);

            $bkash_data = [
                'bkashURL'=>$all_bkash_data->paymentIntent->bkashURL
            ];
        }
        // $message ="  We have received your order. Order no PX".$order['id']."; Our support team will contact you soon to confirm the order.For any kind of query please call: ". $this.contactNumber()." Finesse Lifestyle";
        if($data['paymentType'] == "sslcommerz"){
            $message = "  We have received your order. Order no PX".$order['id']."; Our support team will contact you soon to confirm the order.For any kind of query please call: ". $this.contactNumber(). " Finesse Lifestyle";
       }
    //    $dataObj = [
    //     'number'=>$order['contact'],
    //     'username'=>'01998685230',
    //     'password'=>'HADGZ9FT',
    //     'message'=>$message
    //    ];
        // $options = [
        //     'method'=> 'POST',
        //     'url'=>`http://66.45.237.70/api.php?username=${dataObj.username}&password=${dataObj.password}&number=${dataObj.number}&message=${dataObj.message}`,
        //     'headers'=> [
        //     'Content-Type'=> 'application/x-www-form-urlencoded'
        //     ]
        // ];
        $paidAmdount  =  0;
        $reference_id = "";
        if($order['referralCode']) {
            $ref = Customer::where('barcode',$order['referralCode'])->pluck('id');
            $reference_id = $ref[0];
        }
        $customer_id = Customer::where('userId',$user_id)->first();
        // return $order['id']
        // $order = $order->toArray();
        $saleData = [
            // 'order_id'=>$order->id,
            'order_id'=>71,
            'bonusAmount'=> $data['dgAmount'],
            'roundAmount'=> $order->roundAmount,
            'paidAmount'=> $paidAmdount,
            'coupon'=> $data['coupon'],
            'cashPaid'=> $paidAmdount,
            'customer_id' => "3",
            'date' => $this->datefixed($order->created_at),
            'discount' => $order->discount,
            'homeDeliveryAmount' => $order->shippingPrice,
            'homeDelivery'=>true,
            'reference_id'=> $reference_id,
            'subQuantity'=>0,
            'subTotal'=>$data['subTotal'],
            'invoiceTotal'=>$data['invoiceTotal'],
            'grandTotal'=>($order->grandTotal-$order->shippingPrice),
            'supplier_id'=>'',
            'id'=>$order->id,
            'total'=>($order->grandTotal-$order->shippingPrice),
            'totalQuantity'=>$totalQuantity,
            'type'=>'sell',
            'productDetails'=> $cartItems,
            'refferalDiscount' => $data['refferalDiscount'],
            'membershipDiscount' => $data['membershipDiscount'],
            'promoDiscount' => $data['promoDiscount'],
            'roundingDiscount' => $data['roundAmount'],
            'refferalDiscountAmount' => $data['refferalDiscountAmount'],
            'membershipDiscountAmount' => $data['membershipDiscountAmount'],
            'promoDiscountAmount' => $data['promoDiscountAmount'],
            'giftVoucherCode' => $data['giftVoucherCode'],
            'giftVoucherAmount' => $data['giftVoucherAmount'],

        ];
        $saleData['account_id']  = 1;
        $saleData['store_id']  = 1;
        $saleData['sale_type']  = 2;
        $this->saleOrder($saleData,$date);
        return response()->json([
            'success'=> true,
            'message'=> 'Order  Successfully created ! ',
            "order"=> $order,
            "ssldata"=> $ssldata,
            "bkash_data"=> $bkash_data,
          ],200);
    }
    public function datefixed($value){
        // let value = '2020-10-08 11:38:34'
        $d =  date($value);
        return date('Y',strtotime($d)).'-'.date('m',strtotime($d)) .'-'. date('d',strtotime($d));
        $monthNumber =date('m',strtotime($d)) + 1; //$d.getMonth()+1;
        $monthNumber =array_slice(array("0" + $monthNumber),-2); //("0" + $monthNumber).slice(-2);
        $dayNumber = date('d',strtotime($d));
        $dayNumber = array_slice(array("0" + $dayNumber),-2) ;//("0" + $dayNumber).slice(-2);
        // $today = $d.getFullYear()-$monthNumber-$dayNumber;
        $today = date('Y',strtotime($d)).'-'.$monthNumber.'-'.$dayNumber ;//$d.getFullYear()-$monthNumber-$dayNumber;
        return $today;
    }
    public function saleOrder($input,$date){
        $admin_id = 1;
        $setting =  Setting::where('id',1)->first();
        $mainStore = Store::where('main_branch',1)->with('account')->first();
         $mainStore['main_branch'];
        $input['store_id'] = $mainStore['id'];
        $input['account_id'] = $mainStore['main_branch'];
        $input['sale_type'] = 2;
        $paidAmount = $input['paidAmount'];
        $invoice = Invoice::create([
          'admin_id' => $admin_id,
          'order_id'=> $input['order_id'],
          'store_id' => $input['store_id'],
          'account_id' => $input['account_id'],
          'sale_type' => $input['sale_type'],
          'type' => 'sell',
          'totalQuantity' => $input['totalQuantity'],
          'subTotal' => $input['subTotal'],
          'invoiceTotal' => $input['invoiceTotal'],
          'grandTotal' => $input['grandTotal'],
          'giftVoucher' => $input['giftVoucherCode'],
          'promoCode' => $input['coupon'],
          'refferalDiscount' => $input['refferalDiscount'],
          'membershipDiscount' => $input['membershipDiscount'],
          'promoDiscount' => $input['promoDiscount'],
          'roundingAmount' => $input['roundingDiscount'],
          'refferalDiscountAmount' => $input['refferalDiscountAmount'],
          'membershipDiscountAmount' => $input['membershipDiscountAmount'],
          'promoDiscountAmount' => $input['promoDiscountAmount'],
          'promoCode' =>  $input['coupon'],
          'giftVoucherAmount' => $input['giftVoucherAmount'],
          'paidAmount' => $input['paidAmount'],
          'cashPaid' => $input['paidAmount'],
          'bonusAmount' => $input['bonusAmount'],
          'homeDelivery' => $input['homeDelivery'],
          'homeDeliveryAmount' => $input['homeDeliveryAmount'],
          'customer_id' => $input['customer_id'],
          'date' => $date,
        ]);
         Order::where('id',$input['id'])->update([
            'invoice_id'=>$invoice['id']
        ]);
        $payment = Payment::create([
            'admin_id' => $admin_id,
            'uid' => $input['customer_id'],
            'store_id' => $input['store_id'],
            'account_id' => $input['account_id'],
            'invoice_id' => $invoice['id'],
            'type' => 'incoming',
            'paidAmount' => $input['paidAmount'],
            'date' => $date,
        ]);
        if($input['total'] == $input['paidAmount']){
            $paymentSheet = Paymentsheet::create([

                'admin_id'=> $admin_id,
                'invoice_id'=> $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type'=> 'due',// incoming is profit, outgoing expense, due: due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid'=> $input['customer_id'],
                'amount'=> ($input->total+$input->bonusAmount)*-1,
                'paymentMethod'=> 'cash',
                'remarks'=> 'Sell To Customer',
                'date'=> $date,
            ]);
            $paymentSheet = Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'dueIncoming',// incoming is profit, outgoing expense, due => due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => $input['total']+$input['bonusAmount'],
                'paymentMethod' => 'cash',
                'remarks' => 'Sell To Customer',
                'date' => $date,
            ]);
        } else {
            $paymentSheet = Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'due',// incoming is profit, outgoing expense, due : due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => $input['total']*-1,
                'paymentMethod' => 'due',
                'remarks' => 'Sell To Customer',
                'date' =>$date,
            ]);
            $due = $input['total']  - $paidAmount;
            if($paidAmount){
                $paymentSheet = Paymentsheet::create([
                    'admin_id' => $admin_id,
                    'invoice_id' => $invoice['id'],
                    'store_id' => $input['store_id'],
                    'account_id' => $input['account_id'],
                    'sale_type'=> $input['sale_type'],
                    'type' => 'dueIncoming',// incoming is profit, outgoing expense, due : due for supplier , due for customer
                    'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                    'uid' => $input['customer_id'],
                    'amount' => $paidAmount,
                    'paymentMethod' => 'cash',
                    'remarks' => 'Advance Cash on Sale To Customer',
                    'date' => $date,
                ]);
            }
        }
        if($input['refferalDiscount']){
            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'discount',// incoming is profit, outgoing expense, due => due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($input['refferalDiscountAmount'])*-1,
                'paymentMethod' => 'cash',
                'remarks' => 'Refereral Discount To Customer',
                'date' => $date,
            ]);
        }
        if($input['roundingDiscount']){
            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'discount',// incoming is profit, outgoing expense, due : due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($input['roundingDiscount'])*-1,
                'paymentMethod' => 'cash',
                'remarks' => 'Rounding Discount To Customer',
                'date' => $date,
            ]);
        }
        if($input['membershipDiscount']){
            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'discount',// incoming is profit, outgoing expense, due : due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($input['membershipDiscountAmount'])*-1,
                'paymentMethod' => 'cash',
                'remarks' => 'Membership Discount To Customer',
                'date' => $date,
            ]);
        }
        if($input['promoDiscount']){
            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'discount',// incoming is profit, outgoing expense, due => due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($input['promoDiscountAmount'])*-1,
                'paymentMethod' => 'cash',
                'remarks' => 'Promo Code Discount To Customer',
                'date' => $date,
            ]);
        }
        if($input['bonusAmount']>0){
            $bonust_sheet = Payment::create([
                'admin_id' => $admin_id,
                'uid' => $input['customer_id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'invoice_id' => $invoice['id'],
                'type' => 'incoming',
                'paidAmount' => $input['bonusAmount'],
                'date' => $date,
            ]);

            $bonust_sheet = $bonust_sheet->toJson();
            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'payment_id' => $bonust_sheet['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'dueIncoming',// incoming is profit, outgoing expense, due => due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($input['bonusAmount']),
                'paymentMethod' => 'cash',
                'remarks' => 'DG Payment',
                'date' => $date,
            ]);

            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'bonus',// incoming is profit, outgoing expense, due => due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($input['bonusAmount'])*(-1),
                'paymentMethod' => 'cash',
                'remarks' => 'Customer Bonus Payment',
                'date' => $date,
            ]);

            Bonus::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'customer_id' => $input['customer_id'],
                'amount' => ($input['bonusAmount'])*(-1),
                'type' => 'withdraw',
                'bonusBy' => 0,
                'date' => $date,
            ]);

        }

        if($input['reference_id'] && $input['customer_id']){
            Bonus::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'customer_id' => $input['reference_id'],
                'amount' => ($input['invoiceTotal']*$setting['refererBonus'])/100,
                'type' => 'gift',
                'bonusBy' => $input['customer_id'],
                'date' => $date,
            ]);
        }
        $discountFlag = false;
        $productDiscount = 0;
        foreach($input['productDetails'] as $value){
            if(!$value['mproduct']['discount']) $value['mproduct']['discount'] = 0;
            else {
                $discountFlag = true;
                $productDiscount += parseFloat((($value['mproduct']['sellingPrice']*$value['mproduct']['discount'])/100));
            }
            $stock = $value['quantity'];
            $product_id = $value['productId'];
            $mproduct_id = $value['mproductId'];
            Product::where('id',$product_id)->update([
                'stock'=>DB::raw("stock - $stock")
            ]);
            MainProduct::where('id',$mproduct_id)->update([
                'stock' => DB::raw("stock - $stock")
            ]);

            $profit = $value['mproduct']['sellingPrice'] - $value['vproduct']['averageBuyingPrice'];
            $sellingPrice = $value['mproduct']['sellingPrice'];
            if($value['mproduct']['discount'] > 0){

              $sellingPrice = $value['mproduct']['sellingPrice'] - floor((($value['mproduct']['sellingPrice']*$value['mproduct']['discount'])/100));
            }

            Selling::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'product_id' => $value['productId'],
                'store_id' => $input['store_id'],
                'quantity' => $value['quantity'],
                'unitPrice' => $value['mproduct']['sellingPrice'],
                'sellingPrice' => $sellingPrice,
                'discount' => $value['mproduct']['discount'],
                'profit' => $profit,
                'date' => $date,
            ]);
        }
        if($discountFlag){
            Paymentsheet::create([
                'admin_id' => $admin_id,
                'invoice_id' => $invoice['id'],
                'store_id' => $input['store_id'],
                'account_id' => $input['account_id'],
                'sale_type'=> $input['sale_type'],
                'type' => 'discount',// incoming is profit, outgoing expense, due => due for supplier , due for customer
                'paymentFor'=> 'customer',//  customer mean, I am selling to customer, supllier mean buying from suplier
                'uid' => $input['customer_id'],
                'amount' => ($productDiscount)*(-1),
                'paymentMethod' => 'cash',
                'remarks' => 'Product Discount To Customer',
                'date' => $date,
            ]);
        }
        $data = Invoice::where('type','sell')->where('id',$invoice['id'])->orderBy('id','desc')->with('customer')->first();
        return $data;




    }
    public function checkGiftVoucherCode(Request $request){
        $data = $request->all();
        // return $data['code'];
        $product = GiftVoucher::where('code',$data['code'])->first();
        if($product == null){
            return response()->json([
                'message' => ' Invalid Gift Voucher Code !',
                'success' => true,
            ],401);
        }
        return response()->json([
            'Coupon' => $product,
            'amount' => $product['amount'],
            'message'=> "You Get". $product['amount'] ."Gift Voucher Discount",
            'success'=> true,
        ],202);
    }
    public function checkCoupon(Request $request){
        $data = $request->all();
        $date  = date('Y-m-d');
        $coupon = Coupon::where('code',$data['code'])->first();
        if(!$coupon){
            return response()->json([
                'message'=> 'Invalid Promo Code!',
                'success'=> true,
            ],422);
        }
        $product = Coupon::where('code',$data['code'])->where('validity','>=',$date)->first();
        if(!$product){
            return response()->json([
                'message'=> 'Promo Code expired!',
                'success'=> true,
            ],422);
        }
        return response()->json([
            'Coupon' => $product,
            'discount'=> $product['discount'],
            // 'message'=> `You Get ${product.discount}% Promo-code Discount`,
            'success'=> true,
        ],202);
    }
    public function checkReferralCode(Request $request){
        $data = $request->all();

        $product =  Customer::where('barcode','=',$request->barcode)->count();

        // .query().where('barcode',data.barCode).getCount();
        $setting =  Setting::first();



        if($product > 0){
            return response()->json([
              'Coupon'=> $product,
              'discount'=> $setting['refererBonus'],
              'message'=> "You Get". $setting['refererBonus'] ."Referral Discount",
              'success'=> true,
            ],202);
        }
        return response()->json([
            'message'=> "Invalid Referral Code!",
            'success'=> false,
        ],401);
    }
}
