<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\order;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

   // create order
   public function createOrder(Request $request)
   {
    if(!auth()->check()){
        return response()->json(['message'=>'Unauthorized'],401);
    }
    $Validate = $request->validate(
        [
            'total_amount'=>'nullable|numeric',
            'status'=>'nullable|string|in:pending,completed,cancelled',
        ]
    );
    $order = order::create([
        'user_id'=>auth()->user()->id,
        'total_amount'=>$Validate['total_amount'] ?? 0,
        'status'=>$Validate['status'] ?? 'pending',
    ]);
 
    if (!$order) {
        return response()->json(['message'=>'Order creation failed'],500);
    }
    return response()->json(['message'=>'Order created successfully','order'=>$order],201);
   }
          //list user orders
    public function listUserOrders($userId){
        if(!auth()->check() || auth()->user()->id !== $userId){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $orders= order::where('user_id',$userId)->get();
        
        if ($orders->isEmpty()) {
            return response()->json(['message'=>'No orders found for this user'],404);
        }

        
        return response()->json(['orders' => $orders], 200);
    }
//view order details
public function viewOrderDetails($orderId){
    if(!auth()->check()){
        return response()->json(['message'=>'Unauthorized'],401);
    }
    $order= order::with('orderItems')->find($orderId);
    if (!$order) {
        return response()->json(['message'=>'Order not found'],404);
    }
    //check if the order belongs to the authenticated user
    if($order->user_id !== auth()->user()->id){
        return response()->json(['message'=>'Unauthorized'],401);
    }
    return response()->json(['order' => $order], 200);
    // get all order details from the relation betwwen order and order items and courses
}

 

    //makre order as completed done status 'pending', 'completed', 'cancelled'
    public function orderdone($orderId){
        if(!auth()->check()){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $order= order::with('orderItems')->find($orderId);
        if (!$order) {
            return response()->json(['message'=>'Order not found'],404);
        }
        //check if the order belongs to the authenticated user
        if($order->user_id !== auth()->user()->id){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        
        if ($order->status === 'completed') {
            return response()->json(['message'=>'Order already completed'],400);
        }
        
        $order->status='completed';
        $order->save();
        
        // Create enrollments for all courses in the order
        foreach ($order->orderItems as $item) {
            // Check if enrollment already exists
            $existingEnrollment = \App\Models\enrollment::where('user_id', $order->user_id)
                ->where('course_id', $item->course_id)
                ->first();
                
            if (!$existingEnrollment) {
                \App\Models\enrollment::create([
                    'user_id' => $order->user_id,
                    'course_id' => $item->course_id,
                    'order_item_id' => $item->id,
                    'status' => 'enrolled',
                ]);
            }
        }
        
        return response()->json(['message'=>'Order completed and enrollments created','order'=>$order],200);
    }
   public function cancelOrder($orderId){
        if(!auth()->check()){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $order= order::find($orderId);
        if (!$order) {
            return response()->json(['message'=>'Order not found'],404);
        }
        //check if the order belongs to the authenticated user
        if($order->user_id !== auth()->user()->id){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $order->status='cancelled';
        $order->save();
        return response()->json(['message'=>'Order cancelled','order'=>$order],200);
    }





}
