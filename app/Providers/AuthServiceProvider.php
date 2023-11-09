<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define("superAdmin", function ($user) {
            if (empty($user->level)) {
                return redirect("/logout");
            } else {
                return $user->hasRole('superAdmin');
            }
        });

        Gate::define("operator", function ($user) {
            if (empty($user->level)) {
                return redirect("/logout");
            } else {
                return $user->hasRole('operator');
            }
        });
        Gate::define("client", function ($user) {
            if (empty($user->level)) {
                return redirect("/logout");
            } else {
                return $user->hasRole('client');
            }
        });
    }
}
