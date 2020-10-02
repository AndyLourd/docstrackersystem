<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void 
     */
    public function boot(GateContract $gate)
    {
        // $this->registerPolicies();
        $this->registerPolicies($gate);

        $gate->define('isAdmin', function($user){
            return $user->user_type == 'Admin';
        });

        $gate->define('isUser', function($user){
            return $user->user_type == 'End User';
        });

        $gate->define('isSupply', function($user){
            return $user->user_type == 'Supply';
        });

        $gate->define('isCanvasser', function($user){
            return $user->user_type == 'Canvasser';
        });

        $gate->define('isOOTD', function($user){
            return $user->user_type == 'OOTD';
        });

        $gate->define('isTOD', function($user){
            return $user->user_type == 'TOD';
        });

        $gate->define('isAccountant', function($user){
            return $user->user_type == 'Accountant';
        });

        $gate->define('isCashier', function($user){
            return $user->user_type == 'Cashier';
        });

        $gate->define('isHRMO', function($user){
            return $user->user_type == 'HRMO';
        });
    }
}
