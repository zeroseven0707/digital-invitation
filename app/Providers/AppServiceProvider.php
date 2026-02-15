<?php

namespace App\Providers;

use App\Models\Guest;
use App\Models\Invitation;
use App\Policies\GuestPolicy;
use App\Policies\InvitationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Invitation::class => InvitationPolicy::class,
        Guest::class => GuestPolicy::class,
    ];

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
        // Register policies
        Gate::policy(Invitation::class, InvitationPolicy::class);
        Gate::policy(Guest::class, GuestPolicy::class);
    }
}
