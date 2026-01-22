<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\course;
use App\Models\Order_item;
use App\Models\order;

class OrderItemsController extends Controller
{
    // add course as an item in order

    public function addCourseToOrder(Request $request){
        
        if(!auth()->check()){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $Validate = $request->validate(
            [
                'order_id'=>'required|uuid|exists:orders,id',
                'course_id'=>'required|uuid|exists:courses,id',
                'price'=>'required|numeric|min:0',
            ]
        );
        
        // Verify order belongs to authenticated user
        $order = order::find($Validate['order_id']);
        if ($order->user_id !== auth()->user()->id) {
            return response()->json(['message'=>'Unauthorized - Order does not belong to you'],403);
        }
        
        // Check if course already in order
        $exists = Order_item::where('order_id', $Validate['order_id'])
            ->where('course_id', $Validate['course_id'])
            ->first();
        if ($exists) {
            return response()->json(['message'=>'Course already in order'],400);
        }
        // make the price dynamic based on course price
        $course = course::find($Validate['course_id']);
        $price = $course ? $course->price : $Validate['price'];
        
        $orderItem = Order_item::create([
            'order_id'=>$Validate['order_id'],
            'course_id'=>$Validate['course_id'],
            'price'=>$price,
        ]);
        
        // Update order total
        $order->total_amount += $price;
        $order->save();
        
        return response()->json(['message' => 'Course added to order successfully', 'orderItem' => $orderItem, 'order' => $order], 201);
    }
    // get all order items information from the relation between order items and courses like course title and description and price instructor name evry thing 
    public function geteveryitemdetalis($orderId){
        if(!auth()->check()){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        $order = Order::with('orderItems.course.instructor', 'orderItems.course.category')->find($orderId);
        if (!$order) {
            return response()->json(['message'=>'Order not found'],404);
        }
        
        // Verify order belongs to user
        if ($order->user_id !== auth()->user()->id) {
            return response()->json(['message'=>'Unauthorized - Order does not belong to you'],403);
        }
        
        return response()->json(['order' => $order], 200);
    }
    
    // Remove course from order
    public function removeCourseFromOrder($orderItemId) {
        if(!auth()->check()){
            return response()->json(['message'=>'Unauthorized'],401);
        }
        
        $orderItem = Order_item::find($orderItemId);
        if (!$orderItem) {
            return response()->json(['message'=>'Order item not found'],404);
        }
        
        $order = order::find($orderItem->order_id);
        if ($order->user_id !== auth()->user()->id) {
            return response()->json(['message'=>'Unauthorized'],403);
        }
        
        // Prevent removing from completed orders
        if ($order->status === 'completed') {
            return response()->json(['message'=>'Cannot remove items from completed order'],400);
        }
        
        // Update order total
        $order->total_amount -= $orderItem->price;
        $order->save();
        
        $orderItem->delete();
        
        return response()->json(['message'=>'Course removed from order', 'order' => $order], 200);
    }
}