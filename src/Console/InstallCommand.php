<?php

namespace Riclep\StoryblokForms\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
	protected $name  = 'lsf:install';

	protected $description = 'Create the required components in Storyblok';

	protected $managementClient;

	protected $componentGroups = [];


	public function __construct()
	{
		parent::__construct();

		$this->managementClient = new \Storyblok\ManagementClient(config('storyblok.oauth_token'));
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if (config('storyblok.oauth_token')) {
			$this->makeGroups();
			$this->makeComponents();

		} else {
			$this->error('Please set your management token in the Storyblok config file');
		}

	}


	protected function makeComponents() {
		// TODO - allow publishing and using custom stubs
		$templates = File::files(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR  . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'components');

		foreach ($templates as $template) {
			$json = file_get_contents($template->getRealPath());

			$this->makeComponent(json_decode($json, true));
		}
	}

	protected function makeComponent($payload) {
		// see if UUID is known or name needs replacing
		if (!Str::isUuid($payload['component']['component_group_uuid'])) {
			if ($this->componentGroups->has($payload['component']['component_group_uuid'])) {
				$payload['component']['component_group_uuid'] = $this->componentGroups[$payload['component']['component_group_uuid']]['uuid'];
			} else {
				$this->warn('Requested component group ' . $payload['component']['component_group_uuid'] . ' does not exist, using in root');

				unset($payload['component']['component_group_uuid']);
			}
		}

		$response = $this->managementClient->get('spaces/'.config('storyblok.space_id').'/components/')->getBody();

		if (collect($response['components'])->keyBy('name')->has($payload['component']['name'])) {
			$this->warn('Component already exists: ' . $payload['component']['display_name'] . ' (' .  $payload['component']['name'] . ')');
		} else {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/components/', $payload)->getBody();

			$this->info('Created: ' . $payload['component']['display_name'] . ' (' .  $payload['component']['name'] . ')');
		}
	}

	protected function makeGroups() {
		$this->componentGroups = collect($this->managementClient->get('spaces/'.config('storyblok.space_id').'/component_groups')->getBody()['component_groups'])->keyBy('name');

		if (!$this->componentGroups->has('Forms8')) {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
				'component_group' =>  [
					'name' =>  'Forms8'
				]
			]);
			$this->componentGroups = $this->componentGroups->merge(collect($response->getBody())->keyBy('name'));

			$this->info('Created group: Forms');
		} else {
			$this->warn('Forms component group already exists');
		}

		if (!$this->componentGroups->has('Form fields2')) {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
				'component_group' =>  [
					'name' =>  'Form fields2'
				]
			]);

			$this->componentGroups = $this->componentGroups->merge(collect($response->getBody())->keyBy('name'));

			$this->info('Created group: Form fields');
		} else {
			$this->warn('Form fields component group already exists');
		}

		if (!$this->componentGroups->has('Form validators')) {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
				'component_group' =>  [
					'name' =>  'Form validators'
				]
			]);

			$this->componentGroups = $this->componentGroups->merge(collect($response->getBody())->keyBy('name'));

			$this->info('Created group: Form validators');
		} else {
			$this->warn('Form validators component group already exists');
		}
	}

}
