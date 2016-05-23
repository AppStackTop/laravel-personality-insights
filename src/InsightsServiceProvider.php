<?php

namespace FindBrok\PersonalityInsights;

use FindBrok\PersonalityInsights\Contracts\InsightsContract;
use FindBrok\PersonalityInsights\Facades\PersonalityInsightsFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class InsightsServiceProvider
 *
 * @package FindBrok\PersonalityInsights
 */
class InsightsServiceProvider extends ServiceProvider
{
    /**
     * Define the config path we are using for the Package
     *
     * @var string
     */
    protected $configPath = __DIR__.'/config/personality-insights.php';

    /**
     * Define the implementations contracts maps to which concrete classes
     *
     * @var array
     */
    protected $implementations = [
        InsightsContract::class => PersonalityInsights::class
    ];

    /**
     * Define all Facades here
     *
     * @var array
     */
    protected $facades = [
        'PersonalityInsights' => PersonalityInsightsFacade::class
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Publish config file
        $this->publishes([
            $this->configPath => config_path('personality-insights.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Merge Config File
        $this->mergeConfigFrom($this->configPath, 'personality-insights');
        //Register Bindings
        $this->registerBindings();
        //Register Facades
        $this->registerFacades();
    }

    /**
     * Registers all Interface to Class bindings
     *
     * @return void
     */
    public function registerBindings()
    {
        //Bind Implementations of Interfaces
        collect($this->implementations)->each(function ($class, $interface) {
            //Bind Interface to class
            $this->app->bind($interface, $class);
        });
    }

    /**
     * Registers all facades
     *
     * @return void
     */
    public function registerFacades()
    {
        //Register all facades
        collect($this->facades)->each(function ($facadeClass, $alias) {
            //Add Facade
            $this->app->booting(function () use ($alias,$facadeClass) {
                //Get loader instance
                $loader = AliasLoader::getInstance();
                //Add alias
                $loader->alias($alias, $facadeClass);
            });
        });
    }
}