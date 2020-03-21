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
        //this allows us to easily convert any number in our blade templates to a currency
        Blade::directive('convertNumberToCurrency', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
    }
}
