<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $instructorRole = Role::create(['name' => 'instructor']);
        $studentRole = Role::create(['name' => 'student']);

        // Create Permissions
        $permissions = [
            // User Management
            'manage users',
            'view users',
            
            // Course Permissions
            'view courses',
            'create courses',
            'update courses',
            'delete courses',
            'view course details',
            
            // Section Permissions
            'create sections',
            'update sections',
            'delete sections',
            'view sections',
            
            // Lecture Permissions
            'create lectures',
            'update lectures',
            'delete lectures',
            'view lectures',
            'upload lecture videos',
            
            // Category Permissions
            'view categories',
            'manage categories',
            
            // Order Permissions
            'create orders',
            'view orders',
            'view own orders',
            'view order details',
            'complete orders',
            'cancel orders',
            
            // Order Items Permissions
            'add order items',
            'view order items',
            'remove order items',
            
            // Enrollment Permissions
            'view enrollments',
            'view own enrollments',
            'check enrollment',
            'remove enrollments',
            'manage enrollments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        
        // Admin - Full access to everything
        $adminRole->givePermissionTo(Permission::all());

        // Instructor - Can manage their courses, sections, lectures, and view orders/enrollments
        $instructorRole->givePermissionTo([
            'view courses',
            'create courses',
            'update courses',
            'view course details',
            'create sections',
            'update sections',
            'delete sections',
            'view sections',
            'create lectures',
            'update lectures',
            'delete lectures',
            'view lectures',
            'upload lecture videos',
            'view categories',
            'view own enrollments',
            'view enrollments',
            'check enrollment',
        ]);

        // Student - Can view courses, enroll, manage their orders and enrollments
        $studentRole->givePermissionTo([
            'view courses',
            'view course details',
            'view categories',
            'view sections',
            'view lectures',
            'create orders',
            'view own orders',
            'view order details',
            'cancel orders',
            'add order items',
            'view order items',
            'remove order items',
            'view own enrollments',
            'check enrollment',
        ]);

        
    }
}
