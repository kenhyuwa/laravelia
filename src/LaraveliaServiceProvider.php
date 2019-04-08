<?php

namespace Ken\Laravelia;

use Ken\Laravelia\Facades\Laravelia;
use Ken\Laravelia\Console\Commands\Composer;
use Ken\Laravelia\Console\Commands\Install;
use Illuminate\Support\ServiceProvider;

class LaraveliaServiceProvider extends ServiceProvider
{
	protected $defer = true;

	public function register()
	{
        $this->app->bind('laravelia', function($app) {
			return new Laravelia($app);
		});
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
	            Install::class,
	            Composer::class
	        ]);
	    }

	    $this->load();

	    $this->publishes([
	        __DIR__.'/Publish/config/laravelia.php' => config_path('laravelia.php'),
	        __DIR__.'/Publish/config/config.json' => base_path('config.json'),
	        __DIR__.'/Publish/migrations' => database_path('migrations'),
	        __DIR__.'/Publish/seeds' => database_path('seeds'),
	        __DIR__.'/Publish/public' => public_path('/'),
	    ], 'laravelia');

	    $this->publishes([
	        __DIR__.'/app/ViewComposers/NavigationComposer.php' => app_path('Http/ViewComposers/NavigationComposer.php'),
	    ], 'laravelia_composer');
	}

	public function load()
	{
		$this->loadViewsFrom(__DIR__.'/app/views/v1', 'laravelia');

		require_once __DIR__.'/app/Services/Helpers/app.php';

        view()->composer('*', 'Ken\Laravelia\App\ViewComposers\NavigationComposer');
	}

    /**
     * Register the typical laravelia routes for an application.
     *
     * @param  array  $options
     * @return void
     */
    public static function routes(array $options = [])
    {
        config('laravelia.models.menu')::routes();
        config('laravelia.models.roles')::routes();
        config('laravelia.models.permissions')::routes();
    }
}
