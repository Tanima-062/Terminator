<?php

namespace App\Providers;

use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;


class OpensearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function(){
            return ClientBuilder::create()
                ->setHosts(config('opensearch.hosts'))
                // ->setHosts( [
                //     'host'  => 'https://localhost:9200',
                // ])
                ->setBasicAuthentication(config('opensearch.user'), config('opensearch.password')) // For testing only. Don't store credentials in code.
                ->setSSLVerification(config('opensearch.ssl_verification')) // For testing only. Use certificate for validation
                ->includePortInHostHeader(true)
                ->build()
            ;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
