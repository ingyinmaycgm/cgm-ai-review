<?php

namespace App\Providers;

use App\Services\AIReviewService;
use Illuminate\Support\ServiceProvider;
use OpenAI;

/**
 * Class AppServiceProvider
 *
 * This provider handles the registration of core application services into the 
 * Laravel Service Container. It manages dependency injection settings and 
 * infrastructure bootstrapping.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been registered, 
     * meaning you have access to all other services that have been registered 
     * by the framework.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     *
     * This method binds the AIReviewService as a singleton into the container.
     * It handles the complex instantiation of the OpenAI client, including:
     * 1. Locating the local configuration file in the user's HOME directory.
     * 2. Decoding the Groq API credentials.
     * 3. Configuring the OpenAI factory with the Groq Base URI.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AIReviewService::class, function ($app) {
            $configPath = $_SERVER['HOME'] . '/.ai-review/config.json';
            
            if (!file_exists($configPath)) {
                return new AIReviewService(OpenAI::factory()->withApiKey('')->make());
            }

            $config = json_decode(file_get_contents($configPath), true);

            $client = OpenAI::factory()
                ->withApiKey($config['api_key'] ?? '')
                ->withBaseUri('https://api.groq.com/openai/v1')
                ->make();

            return new AIReviewService($client);
        });
    }
}
