<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // if settings do not exist, (e.g before migrations) , fall back to env/default config
        // try catch block to handle the error
        try {
            if(!\Schema::hasTable('settings')) {
                $this->setStripeKeysFromEnv();
                $this->setPaypalKeysFromEnv();
                return;
            }

            // if settings exist, update the config
            // One round-trip to the database; safer than multiple queries/easy safe access via associative array
            $map = DB::table('settings')->pluck('value', 'name');

            // ********** Stripe **********
            $stripeMode = $map['stripe_mode'] ?? config('stripe.mode', env('STRIPE_MODE', 'test'));
            $stripePublishableKey = $stripeMode === 'test' ? ($map['stripe_test_publishable_key'] ?? config('stripe.pk', env('STRIPE_TEST_PUBLISHABLE_KEY'))) : ($map['stripe_live_publishable_key'] ?? config('stripe.pk', env('STRIPE_LIVE_PUBLISHABLE_KEY')));
            $stripeSecretKey = $stripeMode === 'test' ? ($map['stripe_test_secret_key'] ?? config('stripe.sk', env('STRIPE_TEST_SECRET_KEY'))) : ($map['stripe_live_secret_key'] ?? config('stripe.sk', env('STRIPE_LIVE_SECRET_KEY')));
            Config::set('stripe.mode', $stripeMode);
            Config::set('stripe.pk', $stripePublishableKey);
            Config::set('stripe.sk', $stripeSecretKey);
            
            // ********** Paypal **********
            $paypalMode = $map['paypal_mode'] ?? config('paypal.mode', env('PAYPAL_MODE', 'sandbox'));

            // handle both "sandbox" and "live" typos
            $sandboxSecret = $map['paypal_sandbox_client_secrect'] ?? $map['paypal_sandbox_client_secret'] ?? null;
            $liveSecret = $map['paypal_live_client_secrect'] ?? $map['paypal_live_client_secret'] ?? null;

            if($paypalMode === 'sandbox') {
                $paypalClientId = $map['paypal_sandbox_client_id'] ?? config('paypal.sandbox.client_id', env('PAYPAL_SANDBOX_CLIENT_ID'));
                $paypalClientSecret = $sandboxSecret ?? config('paypal.sandbox.client_secret', env('PAYPAL_SANDBOX_CLIENT_SECRET'));
                $paypalAppId = $map['paypal_sandbox_app_id'] ?? config('paypal.sandbox.app_id', env('PAYPAL_SANDBOX_APP_ID'));
            }
            else {
                $paypalClientId = $map['paypal_live_client_id'] ?? config('paypal.live.client_id', env('PAYPAL_LIVE_CLIENT_ID'));
                $paypalClientSecret = $liveSecret ?? config('paypal.live.client_secret', env('PAYPAL_LIVE_CLIENT_SECRET'));
                $paypalAppId = $map['paypal_live_app_id'] ?? config('paypal.live.app_id', env('PAYPAL_LIVE_APP_ID'));
            }

            $paypalNotifyUrl = $map['paypal_notify_url'] ?? config('paypal.notify_url', env('PAYPAL_NOTIFY_URL'));
            Config::set('paypal.mode', $paypalMode);
            Config::set("paypal.{$paypalMode}.client_id", $paypalClientId);
            Config::set("paypal.{$paypalMode}.client_secret", $paypalClientSecret);
            Config::set("paypal.{$paypalMode}.app_id", $paypalAppId);
            Config::set('paypal.notify_url', $paypalNotifyUrl);

        } catch (\Throwable $e) {
            // any failure, fall back to env/default config so app still boots
            logger()->warning('PaymentGatewayServiceProvider: Database not configured or connection not ready yet -> fall back to env/config used: '. $e->getMessage());
            // 
            $this->setStripeKeysFromEnv();
            $this->setPaypalKeysFromEnv();

        }

        
    }

    // New Method: setKeysFrom Env(refactoring the old method)
    private function setStripeKeysFromEnv(): void {
        $mode = config('stripe.mode', env('STRIPE_MODE', 'test'));
        Config::set('stripe.mode', $mode);

        // Default configs per mode
        $pk = $mode === 'test' ? env('STRIPE_TEST_PUBLISHABLE_KEY') : env('STRIPE_LIVE_PUBLISHABLE_KEY');
        $sk = $mode === 'test' ? env('STRIPE_TEST_SECRET_KEY') : env('STRIPE_LIVE_SECRET_KEY');

        Config::set('stripe.pk', $pk);
        Config::set('stripe.sk', $sk);
    }

    private function setPaypalKeysFromEnv(): void {
        $mode = config('paypal.mode', env('PAYPAL_MODE', 'sandbox'));
        Config::set('paypal.mode', $mode);

        if($mode === 'sandbox') {
            Config::set('paypal.sandbox.client_id', env('PAYPAL_SANDBOX_CLIENT_ID'));
            Config::set('paypal.sandbox.client_secret', env('PAYPAL_SANDBOX_CLIENT_SECRET'));
            Config::set('paypal.sandbox.app_id', env('PAYPAL_SANDBOX_APP_ID'));
            Config::set('paypal.notify_url', env('PAYPAL_NOTIFY_URL'));
        } else {
            Config::set('paypal.live.client_id', env('PAYPAL_LIVE_CLIENT_ID'));
            Config::set('paypal.live.client_secret', env('PAYPAL_LIVE_CLIENT_SECRET'));
            Config::set('paypal.live.app_id', env('PAYPAL_LIVE_APP_ID'));
        }

        Config::set('paypal.notify_url', env('PAYPAL_NOTIFY_URL'));
    }



}