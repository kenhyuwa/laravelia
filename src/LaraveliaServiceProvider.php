<?php

namespace Ken\Laravelia;

use Ken\Laravelia\Facades\Laravelia;
use Ken\Laravelia\Console\Commands\NavigationComposer;
use Ken\Laravelia\Console\Commands\InstallLaravelia;
use Illuminate\Support\ServiceProvider;

class LaraveliaServiceProvider extends ServiceProvider
{
	protected $defer = true;

	/**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laravelia', function($app) {
			return new Laravelia($app);
		});
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Laravelia::class];
    }

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		if ($this->app->runningInConsole()) {
	        $this->commands([
	            NavigationComposer::class,
	            InstallLaravelia::class,
	        ]);
	    }
	    $this->load();

	    $this->publishes([
	        __DIR__.'/Publish/config/config.json' => base_path('laravelia.config.json'),
	        __DIR__.'/Publish/database/migrations/' => database_path('migrations'),
	        __DIR__.'/Publish/database/seeds/' => database_path('seeds'),
	        // __DIR__.'/Publish/public' => public_path('/'),
	    ], 'laravelia');

	    $this->publishes([
	    	__DIR__.'/Publish/config/laratrust.php' => config_path('laratrust.php'),
	    	__DIR__.'/Publish/config/laravelia.php' => config_path('laravelia.php'),
	    ], 'config-laravelia');
	}

	public function load()
	{
		$this->mergeConfigFrom(__DIR__.'/Publish/config/laravelia.php', 'laravelia');

		$this->mergeConfigFrom(__DIR__.'/Publish/config/laratrust.php', 'laratrust');
		
		$this->loadViewsFrom(__DIR__.'/app/views/v1', 'laravelia');

        view()->composer('*', 'Ken\Laravelia\App\ViewComposers\NavigationComposer');
	}
}
