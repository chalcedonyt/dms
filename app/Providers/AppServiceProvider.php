<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \DrewM\MailChimp\MailChimp;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('GoogleClient', function ($app): \Google_Client {
            if (!\Auth::user()) {
                throw new \ErrorException("Not logged in");
            }
            $access_token = \Auth::user()->google_token;
            $client = new \Google_Client();
            $client->setAccessToken($access_token);
            return $client;
        });

        $this->app->singleton(Mailchimp::class, function ($app): Mailchimp {
            return new MailChimp(env('MC_KEY'));
        });
    }
}
