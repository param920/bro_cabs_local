<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{

// use Braintree\Configuration as Braintree_Configuration;
    /**
     * Validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $validator;

    /**
     * Bootstrap any application services.
     *
     * @param \Illuminate\Contracts\Validation\Factory $validator
     * @return void
     */
    public function boot(Validator $validator) : void
    {
        // \Braintree_Configuration::environment(env('BTREE_ENVIRONMENT'));
        // \Braintree_Configuration::merchantId(env('BTREE_MERCHANT_ID'));
        // \Braintree_Configuration::publicKey(env('BTREE_PUBLIC_KEY'));
        // \Braintree_Configuration::privateKey(env('BTREE_PRIVATE_KEY'));

        $this->validator = $validator;

        Schema::defaultStringLength(191);

        $this->loadCustomValidators();

        Paginator::useBootstrap();

        // Defensive DB usage: safe in CI/console BEFORE migrations


        try{
            if (Schema::hasTable('settings')) {
                $navs = Setting::whereName('nav_color')->firstOrNew([]);
                view()->share('navs', $navs);
    
                $side = Setting::whereName('sidebar_color')->firstOrNew([]);
                view()->share('side', $side);
    
                $side_txt = Setting::whereName('sidebar_text_color')->firstOrNew([]);
                view()->share('side_txt', $side_txt);
            } else {
    
                // create a shareUiDefaultSettings() method
                $this->shareUiDefaultSettings();
    
                view()->share('navs', "#0B4DD8");
    
                view()->share('side', "#2a3042");
    
                view()->share('side_txt', "#a2a5af");
            }
        } catch (\Throwable $e) {
            // log the error
            // DB not configured or connection not ready yet -> fall back to default settings
            logger()->warning('Database not configured or connection not ready yet -> fall back to default used: '. $e->getMessage());
            $this->shareUiDefaultSettings();
        }
        

    }

    // sharedUiDefaultSettings()
    protected function shareUiDefaultSettings()
    {
        view()->share('navs', "#0B4DD8");
        view()->share('side', "#2a3042");
        view()->share('side_txt', "#a2a5af");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

            if (app_debug_enabled()) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }
        }
    }

    /**
     * Load the custom validator methods.
     *
     * @return void
     */
    protected function loadCustomValidators() : void
    {
        $customValidatorClass = 'App\Base\Validators\CustomValidators';

        $this->extendValidator('mobile_number', $customValidatorClass);
        $this->extendValidator('numeric_max', $customValidatorClass);
        $this->extendValidator('numeric_min', $customValidatorClass);
        $this->extendValidator('otp', $customValidatorClass);
        $this->extendValidator('uuid', $customValidatorClass);
        $this->extendValidator('decimal', $customValidatorClass);
        $this->extendValidator('double', $customValidatorClass);
    }

    /**
     * Extend the validator with custom methods.
     *
     * @param string $name
     * @param string $class
     * @return void
     */
    protected function extendValidator($name, $class) : void
    {
        $method = 'validate' . Str::studly($name);

        $this->validator->extend($name, "{$class}@{$method}");
    }

    public function nav() : void
    {

        try {
            if (Schema::hasTable('settings')) {

            $navs = Setting::whereName('nav_color')->first();
            view()->share('navs', $navs);
            return;
            }
        } catch (\Throwable $e) {}
        view()->share('navs', "#0B4DD8");
    }

    public function side() 
    {
        try {
            if (Schema::hasTable('settings')) {
                $side = Setting::whereName('sidebar_color')->first();
                view()->share('side', $side);
                return;
            }
        } catch (\Throwable $e) {}
        view()->share('side', "#2a3042");
    }
    
    public function sidetxt() 
    {

        try {
            if(Schema::hasTable('settings')) {
                $side_txt = Setting::whereName('sidebar_text_color')->first();
                view()->share('side_txt', $side_txt);
                return;
            }
        } catch (\Throwable $e) {}
        view()->share('side_txt', "#a2a5af");
    }


}
