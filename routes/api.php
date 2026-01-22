<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\EnrollmentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|-------------------------------------------------------------------------- 
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/categories', [CourseController::class, 'getCategories']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::post('/courses', [CourseController::class, 'store'])->middleware('auth:sanctum');
Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('auth:sanctum');

// Sections & Lectures
Route::post('/courses/{courseId}/sections', [CourseController::class, 'createSection'])->middleware('auth:sanctum');
Route::post('/sections/{sectionId}/lectures', [CourseController::class, 'addLecture'])->middleware('auth:sanctum');
Route::post('/lectures/upload-video', [CourseController::class, 'uploadLectureVideo'])->middleware('auth:sanctum');

// Get sections and lectures (public)
Route::get('/courses/{courseId}/sections', [CourseController::class, 'getSections']);
Route::get('/sections/{sectionId}/lectures', [CourseController::class, 'getLectures']);

// Orders & Cart
Route::post('/orders', [OrdersController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/users/{userId}/orders', [OrdersController::class, 'listUserOrders'])->middleware('auth:sanctum');
Route::get('/orders/{orderId}', [OrdersController::class, 'viewOrderDetails'])->middleware('auth:sanctum');
Route::put('/orders/{orderId}/complete', [OrdersController::class, 'orderdone'])->middleware('auth:sanctum');
Route::put('/orders/{orderId}/cancel', [OrdersController::class, 'cancelOrder'])->middleware('auth:sanctum');

// Order Items
Route::post('/order-items', [OrderItemsController::class, 'addCourseToOrder'])->middleware('auth:sanctum');
Route::get('/orders/{orderId}/items', [OrderItemsController::class, 'geteveryitemdetalis'])->middleware('auth:sanctum');
Route::delete('/order-items/{orderItemId}', [OrderItemsController::class, 'removeCourseFromOrder'])->middleware('auth:sanctum');

// Enrollments
Route::get('/enrollments', [EnrollmentController::class, 'getUserEnrollments'])->middleware('auth:sanctum');
Route::get('/enrollments/{enrollmentId}', [EnrollmentController::class, 'getEnrollmentDetails'])->middleware('auth:sanctum');
Route::get('/courses/{courseId}/check-enrollment', [EnrollmentController::class, 'checkEnrollment'])->middleware('auth:sanctum');
Route::delete('/enrollments/{enrollmentId}', [EnrollmentController::class, 'removeEnrollment'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

