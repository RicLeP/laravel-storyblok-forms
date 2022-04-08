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
		// TODO remove
		//$xx = $this->managementClient->get('spaces/'.config('storyblok.space_id').'/components/')->getBody();

		//dd($xx);

		if (config('storyblok.oauth_token')) {
			$this->makeGroups();
			$this->makeComponents();

		} else {
			$this->error('Please set your management token in the Storyblok config file');
		}

	}


	protected function makeComponents() {
		// TODO - allow publishing and using custom stubs
		$templates = File::allFiles(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR  . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'components');

		foreach ($templates as $template) {
			$json = file_get_contents($template->getRealPath());

			$schema = json_decode($json, true);

			// TODO - validate json.....

			if ($schema) {
				$this->makeComponent($schema);
			}
		}
	}

	protected function makeComponent($payload) {
		// see if UUID is known or name needs replacing
		if (!Str::isUuid($payload['component']['component_group_uuid'])) {
			if ($uuid = $this->groupUuidFromName($payload['component']['component_group_uuid'])) {
				$payload['component']['component_group_uuid'] = $uuid;
			} else {
				$this->warn('Requested component group ' . $payload['component']['component_group_uuid'] . ' does not exist, using in root');

				unset($payload['component']['component_group_uuid']);
			}
		}


		// Bloks fields - set up component group whitelist
		foreach ($payload['component']['schema'] as $fieldKey => $field) {
			if ($field['type'] === 'bloks' && array_key_exists('component_group_whitelist', $field)) {
				foreach ($field['component_group_whitelist'] as $groupKey => $group) {
					if ($uuid = $this->groupUuidFromName($group)) {
						$payload['component']['schema'][$fieldKey]['component_group_whitelist'][$groupKey] = $uuid;
					} else {
						$this->warn('Requested blok component group whitelist ' . $payload['component']['component_group_uuid'] . ' does not exist');

						unset($payload['component']['component_group_uuid']);
					}
				}
			}
		}

		// TODO - presets in schema such as adding validators for numeric inputs
		/* TODO - field tabs
		  "tab-e452755a-71ed-40fb-a735-4fc96c1a1e78" => array:4 [
          "type" => "tab"
          "display_name" => "Settings"
          "keys" => array:2 [
            0 => "placeholder"
            1 => "type"
          ]
          "pos" => 5
        ]
        "tab-54270608-a51e-48b9-a375-9c6111004250" => array:4 [
          "type" => "tab"
          "display_name" => "Validation"
          "keys" => array:1 [
            0 => "validators"
          ]
          "pos" => 6
        ]
		 * */

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

		if (!$this->componentGroups->has('Forms')) {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
				'component_group' =>  [
					'name' =>  'Forms'
				]
			]);
			$this->componentGroups = $this->componentGroups->merge(collect($response->getBody())->keyBy('name'));

			$this->info('Created group: Forms');
		} else {
			$this->warn('Forms component group already exists');
		}

		if (!$this->componentGroups->has('Form fields')) {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
				'component_group' =>  [
					'name' =>  'Form fields'
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


	protected function groupUuidFromName($name) {
		if ($this->componentGroups->has($name)) {
			return $this->componentGroups[$name]['uuid'];
		} else {
			return false;
		}
	}

}
