<?php namespace Buzz;

use Buzz\Browser;
use Buzz\Client\FileGetContents;
use Illuminate\Support\ServiceProvider;

class BuzzServiceProvider extends ServiceProvider {

/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app['config']->package('sirsquall/buzz', 'buzz');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['buzz'] = $this->app->share(function($app)
		{
      $client = new FileGetContents(null, $app['config']);
			return new Browser($client);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Buzz');
	}

}
