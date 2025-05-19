<?php

namespace mayanksinh\AiContentGenerator;

use Illuminate\Support\ServiceProvider;

class AiContentGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/ai-content-generator.php', 'ai-content-generator'
        );

        $this->app->singleton('ai-content-generator', function ($app) {
            $config = $app['config']->get('ai-content-generator');
            $apiKey = $config['gemini_api_key'] ?? '';
            
            if (empty($apiKey)) {
                throw new \Exception('Gemini API key is not set. Please set GEMINI_API_KEY in your .env file.');
            }
            
            return new AiContentGenerator($apiKey);
        });

        $this->app->alias('ai-content-generator', AiContentGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ai-content-generator.php' => config_path('ai-content-generator.php'),
        ], 'ai-content-generator-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                // Add any console commands here if needed
            ]);
        }
    }
} 