<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Carbon::macro('greet', function () {
            $hour = $this->format('H');
            if ($hour < 12) {
                return 'Selamat Pagi';
            }
            if ($hour < 15) {
                return 'Selamat Siang';
            }
            if ($hour < 19) {
                return 'Selamat Sore';
            }
            return 'Selamat Malam';
        });
    }
}
