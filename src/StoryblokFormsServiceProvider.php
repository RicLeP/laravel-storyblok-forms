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
	    $this->commands([
		    InstallCommand::class,
	    ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
