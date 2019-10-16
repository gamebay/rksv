<?php


namespace Gamebay\Rksv\ServiceProvider;


use Illuminate\Support\ServiceProvider;

class RKSVServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // Allow your user to publish the config
        $this->publishes([
            __DIR__ . '/Config/RKSV.php' => config_path('RKSV.php'),
        ], 'config');

    }


    public function register()
    {
        // Load the config file and merge it with the user's (should it get published)
        $this->mergeConfigFrom(__DIR__ . '/Config/RKSV.php', 'RKSV_PRIMESIGN_BASE_CERTIFICATE_URL');
        $this->mergeConfigFrom(__DIR__ . '/Config/RKSV.php', 'RKSV_AUSTRIAN_TAX_NUMBER');
        $this->mergeConfigFrom(__DIR__ . '/Config/RKSV.php', 'RKSV_PRIMESIGN_TOKEN_KEY');
        $this->mergeConfigFrom(__DIR__ . '/Config/RKSV.php', 'RKSV_PRIMESIGN_RECEIPT_SIGN_URL');
    }
}