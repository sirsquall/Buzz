<?php namespace Buzz;

use Buzz\Browser;
use Illuminate\Support\ServiceProvider;

class BuzzServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app;

    $app->bind('buzz', function($app) {
      return new Browser();
    });
	}

}
