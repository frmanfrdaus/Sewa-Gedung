<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Pembatalan;
use App\Models\Pembukuan;
use App\Models\Gedung;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        /**
         * Register policy
         */
        Gate::define('read-users', function ($user) {
            return $user->role == 'admin';
        });

         //Register Pemesanan Policy
        Gate::define('read-pemesanans', function ($user) {
            return $user->role == 'user' || $user->role == 'admin';  
        });
        Gate::define('store-pemesanans', function ($user) {
            return $user->role == 'user' || $user->role == 'admin';  
        });
        Gate::define('update-pemesanans', function ($user, $pemesanans) {
            // chech is admin or not
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            }
        });
        Gate::define('delete-pemesanans', function ($user, $pemesanans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('show-pemesanans', function ($user, $pemesanans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        //register Pembayaran policy
        Gate::define('read-pembayarans', function ($user) {
            return $user->role == 'user' || $user->role == 'admin';  
        });
        Gate::define('store-pembayarans', function ($user) {
            return $user->role == 'user' || $user->role == 'admin';   
        });
        Gate::define('update-pembayarans', function ($user, $pembayarans) {
            // chech is admin or not
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            }
        });
        Gate::define('delete-pembayarans', function ($user, $pembayarans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('show-pembayarans', function ($user, $pembayarans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });

        //register policy pembatalan
        /*Gate::define('read-pembatalans', function ($user, $pembatalans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });*/
        Gate::define('read-pembatalans', function ($user) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            }   
        });
        Gate::define('store-pembatalans', function ($user, $pembatalans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('update-pembatalans', function ($user, $pembatalans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('show-pembatalans', function ($user, $pembatalans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('delete-pembatalans', function ($user, $pembatalans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        //pembukuan policy
        Gate::define('read-pembukuans', function ($user) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('show-pembukuans', function ($user, $pembukuans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('update-pembukuans', function ($user, $pembukuans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('store-pembukuans', function ($user, $pembukuans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('delete-pembukuans', function ($user, $pembukuans) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        //gedung
        Gate::define('read-gedungs', function ($user) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('show-gedungs', function ($user, $gedungs) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('update-gedungs', function ($user, $gedungs) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('store-gedungs', function ($user, $gedungs) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        Gate::define('delete-gedungs', function ($user, $gedungs) {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            } 
        });
        
        /** Register policy end */


        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
