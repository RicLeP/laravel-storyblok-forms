<?php

namespace Riclep\StoryblokForms\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionClass;
use Riclep\StoryblokForms\StoryblokFormsServiceProvider;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class TestCase extends Orchestra
{
	use InteractsWithViews;

	protected function getPackageProviders($app)
	{
		return [StoryblokFormsServiceProvider::class];
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{

		parent::getEnvironmentSetUp($app);

		$viewPath = str_replace('..', '', __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);


		$app['config']->set('storyblok.component_class_namespace', ['Riclep\StoryblokForms\\']);
		$app['config']->set('storyblok.view_path', $viewPath);

		$app['config']->set('view.paths', array_merge(config('view.paths'), [$viewPath]));
		//dd(config('view.paths'));
	}

	protected function bootRequest() {
		$this->app['router']->get('test', ['middleware' => 'web', 'uses' => function (){
			return 'hello world';
		}]);

		$this->call('GET', 'test');
	}
}
