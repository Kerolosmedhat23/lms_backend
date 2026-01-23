<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\course;
use App\Models\order;
use App\Models\enrollment;
use App\Policies\CoursePolicy;
use App\Policies\OrderPolicy;
use App\Policies\EnrollmentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies for OBAC
        Gate::policy(course::class, CoursePolicy::class);
        Gate::policy(order::class, OrderPolicy::class);
        Gate::policy(enrollment::class, EnrollmentPolicy::class);
    }
}
