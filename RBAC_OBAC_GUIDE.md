# RBAC + OBAC Implementation Guide

## Overview
This LMS system implements a **two-layer security system**:
1. **RBAC (Role-Based Access Control)** - Permission-based authorization
2. **OBAC (Object-Based Access Control)** - Object ownership authorization using Laravel Policies

## Policies Implementation

### 1. CoursePolicy (`app/Policies/CoursePolicy.php`)

| Method | Description | Authorization Logic |
|--------|-------------|---------------------|
| `viewAny()` | View course list | Anyone can view |
| `view()` | View single course | Anyone can view |
| `create()` | Create new course | Instructors & Admins only |
| `update()` | Update course | Admin OR course owner |
| `delete()` | Delete course | Admin OR course owner |
| `manageSection()` | Manage course sections | Admin OR course owner |
| `manageLecture()` | Manage course lectures | Admin OR course owner |
| `forceDelete()` | Permanently delete | Admins only |

**Used in:** `CourseController`
- `store()` - Uses `create` policy
- `show()` - Uses `view` policy
- `update()` - Uses `update` policy
- `createSection()` - Uses `manageSection` policy
- `addLecture()` - Uses `manageLecture` policy

---

### 2. OrderPolicy (`app/Policies/OrderPolicy.php`)

| Method | Description | Authorization Logic |
|--------|-------------|---------------------|
| `viewAny()` | View orders list | All authenticated users |
| `view()` | View single order | Admin OR order owner |
| `create()` | Create order | Students only |
| `update()` | Update order | Admin OR order owner |
| `complete()` | Complete order | Admin OR order owner |
| `cancel()` | Cancel order | Admin OR order owner |
| `manageItems()` | Manage order items | Admin OR order owner |
| `delete()` | Delete order | Admins only |
| `forceDelete()` | Permanently delete | Admins only |

**Used in:** `OrdersController` & `OrderItemsController`
- `createOrder()` - Uses `create` policy
- `listUserOrders()` - Uses `viewAny` policy
- `viewOrderDetails()` - Uses `view` policy
- `orderdone()` - Uses `complete` policy
- `cancelOrder()` - Uses `cancel` policy
- `addCourseToOrder()` - Uses `manageItems` policy
- `geteveryitemdetalis()` - Uses `view` policy
- `removeCourseFromOrder()` - Uses `manageItems` policy

---

### 3. EnrollmentPolicy (`app/Policies/EnrollmentPolicy.php`)

| Method | Description | Authorization Logic |
|--------|-------------|---------------------|
| `viewAny()` | View enrollments list | All authenticated users |
| `view()` | View single enrollment | Admin OR enrollment owner OR course instructor |
| `create()` | Create enrollment | Admins only (auto-created via orders) |
| `update()` | Update enrollment | Admins only |
| `delete()` | Delete enrollment | Admin OR enrollment owner |
| `forceDelete()` | Permanently delete | Admins only |

**Used in:** `EnrollmentController`
- `getUserEnrollments()` - Uses RBAC permission check
- `getEnrollmentDetails()` - Uses `view` policy
- `checkEnrollment()` - Uses RBAC permission check
- `removeEnrollment()` - Uses `delete` policy

---

## How RBAC + OBAC Work Together

### Example: Updating a Course

```php
// In CourseController@update

// Step 1: RBAC - Check if user has the permission
if (!$request->user()->can('update courses')) {
    return response()->json(['message' => 'No permission'], 403);
}

// Step 2: OBAC - Check if user can update THIS specific course
$this->authorize('update', $course); // Checks CoursePolicy@update

// Logic:
// - If user is Admin → ✅ Allowed (can update any course)
// - If user is course owner → ✅ Allowed (can update own course)
// - Otherwise → ❌ Denied
```

### Example: Viewing an Order

```php
// In OrdersController@viewOrderDetails

// Step 1: OBAC - Check object-level access
$this->authorize('view', $order); // Checks OrderPolicy@view

// Logic:
// - If user is Admin → ✅ Can view any order
// - If user is order owner → ✅ Can view own order
// - Otherwise → ❌ Denied
```

---

## Roles & Their Capabilities

### Admin
- ✅ Full access to all resources
- ✅ Can manage any course, order, or enrollment
- ✅ Bypass ownership checks
- ✅ Can permanently delete resources

### Instructor
- ✅ Can create and manage own courses
- ✅ Can create sections and lectures for own courses
- ✅ Can upload videos
- ✅ Can view enrollments for own courses
- ❌ Cannot manage other instructors' courses
- ❌ Cannot create orders

### Student
- ✅ Can view all courses
- ✅ Can create and manage own orders
- ✅ Can add/remove items from own orders
- ✅ Can view and manage own enrollments
- ❌ Cannot create courses
- ❌ Cannot manage other students' orders

---

## Security Flow

```
User Request
    ↓
Authentication (Sanctum)
    ↓
RBAC Layer (Spatie Permissions)
    → Does user have required permission?
    ↓ YES
OBAC Layer (Laravel Policies)
    → Can user access THIS specific object?
    ↓ YES
Action Allowed
```

---

## Registration with Role Assignment

When users register, they can specify a role:

```json
POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "student"  // Optional: student, instructor, admin
}
```

Default role: **student**

---

## Policy Registration

All policies are registered in `AppServiceProvider.php`:

```php
Gate::policy(course::class, CoursePolicy::class);
Gate::policy(order::class, OrderPolicy::class);
Gate::policy(enrollment::class, EnrollmentPolicy::class);
```

---

## Testing Policies

You can test policies in your application:

```php
// Check if user can update a course
if ($user->can('update', $course)) {
    // User can update
}

// Check if user can create orders
if ($user->can('create', Order::class)) {
    // User can create
}
```

---

## Benefits of RBAC + OBAC

1. **Separation of Concerns**: Roles define capabilities, Policies define ownership
2. **Fine-grained Control**: Permission + Object-level checks
3. **Scalability**: Easy to add new roles or modify policies
4. **Security**: Layered authorization prevents unauthorized access
5. **Maintainability**: Centralized policy logic instead of scattered checks
6. **Flexibility**: Can override policies for specific use cases (e.g., admin access)
