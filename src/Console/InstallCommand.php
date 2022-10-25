<?php

namespace Riclep\StoryblokForms\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Riclep\StoryblokForms\Support\ComponentGroupMaker;
use Riclep\StoryblokForms\Support\ComponentMaker;
use Storyblok\ManagementClient;

class InstallCommand extends Command
{
	// TODO - refactor to use Laravel Storyblok CLI package

	/**
	 * @var string
	 */
	protected $name  = 'lsf:install';

	/**
	 * @var string
	 */
	protected $description = 'Create the required components for Storyblok forms';

	/**
	 * @var ManagementClient
	 */
	protected $managementClient;

	/**
	 * @var array
	 */
	protected $componentGroups = [];


	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->managementClient = new ManagementClient(config('storyblok.oauth_token'));
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		if (config('storyblok.oauth_token')) {
			(new ComponentGroupMaker($this))->import();

			$this->makeComponents();

		} else {
			$this->error('Please set your management token in the Storyblok config file');
		}

	}


	/**
	 * @throws \JsonException
	 */
	protected function makeComponents(): void
	{
		// TODO - allow publishing and using custom stubs
		$templates = File::allFiles(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR  . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'components');

		foreach ($templates as $template) {
			$json = file_get_contents($template->getRealPath());

			$schema = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

			if ($schema) {
				(new ComponentMaker($this, $schema))->import();
			}
		}
	}
}
