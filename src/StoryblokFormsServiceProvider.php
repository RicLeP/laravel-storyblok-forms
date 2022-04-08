<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\ServiceProvider;
use Riclep\StoryblokForms\Console\InstallCommand;


class StoryblokFormsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
	    if ($this->app->runningInConsole()) {
		    $this->publishes([
			    __DIR__.'/../config/storyblok-forms.php' => config_path('storyblok-forms.php'),
		    ], 'storyblok-forms');
	    }

	    $this->commands([
		    InstallCommand::class,
	    ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
	    $this->mergeConfigFrom(__DIR__.'/../config/storyblok-forms.php', 'storyblok-forms');
    }
}
