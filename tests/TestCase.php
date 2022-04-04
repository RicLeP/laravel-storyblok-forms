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
		$viewPath = str_replace('..', '', __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);


		$app['config']->set('storyblok.component_class_namespace', ['Riclep\StoryblokForms\Tests\Fixtures\\']);
		$app['config']->set('storyblok.view_path', $viewPath);

		$app['config']->set('view.paths', array_merge(config('view.paths'), [$viewPath]));
		//dd(config('view.paths'));
	}

	protected function makePage($file = null) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/' . ($file ?: 'all-fields.json')), true);

		if ($file) {
			$class = config('storyblok.component_class_namespace') . 'Pages\\' . Str::studly($story['story']['content']['component']);
		} else {
			$class = config('storyblok.component_class_namespace') . 'Page';
		}

		return new $class($story['story']);
	}

	public static function callMethod($obj, $name, array $args) {
		$class = new ReflectionClass($obj);
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method->invokeArgs($obj, $args);
	}
}
