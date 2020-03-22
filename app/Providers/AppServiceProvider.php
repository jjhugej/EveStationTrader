<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::directive('convertNumberToCurrency', function ($number) {
            //this allows us to easily convert any number in our blade templates to a currency
            // to use in blade start with @convertNumberToCurrency($num)
            return "<?php echo number_format($number, 2); ?>";
        });

        Blade::directive('formatNumber', function ($number) {
            //this converts a number without comma seperation to one with
            // i.e. 20000 -> 20,000
            // to use in blade start with @formatNumber($num)
            return "<?php echo number_format($number); ?>";
        });
    }
}
