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

		$app['request']->setLaravelSession($app['session']->driver('array'));

		$app['config']->set('storyblok.component_class_namespace', ['App\Storyblok\\', 'Riclep\StoryblokForms\\']);
		$app['config']->set('storyblok.settings_field', 'settings');

	}

	protected function makePage($file = null) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/' . ($file ?: 'all-fields.json')), true);

		if ($file) {
			$class = config('storyblok.component_class_namespace') . 'Pages\\' . Str::studly($story['story']['content']['component']);
		} else {
			$class = config('storyblok.component_class_namespace')[0] . 'Page';
		}

		return new $class($story['story']);
	}
}
