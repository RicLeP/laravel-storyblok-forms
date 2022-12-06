<?php

namespace Riclep\StoryblokForms;

use Illuminate\Support\ServiceProvider;
use Riclep\StoryblokForms\Console\InstallCommand;


class StoryblokFormsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
	    if ($this->app->runningInConsole()) {
		    $this->publishes([
			    __DIR__.'/../config/storyblok-forms.php' => config_path('storyblok-forms.php'),
			    __DIR__.'/../stubs/views' => resource_path('views/storyblok')
		    ], 'storyblok-forms');
	    }

	    $this->commands([
		    InstallCommand::class,
	    ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
	    $this->mergeConfigFrom(__DIR__.'/../config/storyblok-forms.php', 'storyblok-forms');
    }
}
