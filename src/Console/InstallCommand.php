<?php

namespace Riclep\StoryblokForms\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
	protected $name  = 'lsf:install';

	protected $description = 'Create the required components in Storyblok';


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if (config('storyblok.oauth_token')) {
			$managementClient = new \Storyblok\ManagementClient(config('storyblok.oauth_token'));

			$groups = collect($managementClient->get('spaces/'.config('storyblok.space_id').'/component_groups')->getBody()['component_groups'])->keyBy('name');

			if (!$groups->has('Forms')) {
				$managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
					'component_group' =>  [
						'name' =>  'Forms'
					]
				]);
			} else {
				$this->info('Component Group already exists');
			}

			if (!$groups->has('Form fields')) {
				$managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
					'component_group' =>  [
						'name' =>  'Form fields'
					]
				]);
			} else {
				$this->info('Component Group already exists');
			}

			if (!$groups->has('Form validators')) {
				$managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
					'component_group' =>  [
						'name' =>  'Form validators'
					]
				]);
			} else {
				$this->info('Component Group already exists');
			}


		}

		$this->error('Please set your management token in the Storyblok config file');
	}


}
