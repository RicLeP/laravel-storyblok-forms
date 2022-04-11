<?php

namespace Riclep\StoryblokForms\Support;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Console\InstallCommand;
use Storyblok\ManagementClient;

class ComponentMaker
{
	/**
	 * @var
	 */
	protected $payload;

	/**
	 * @var
	 */
	protected $componentGroups;

	/**
	 * @var ManagementClient
	 */
	protected $managementClient;

	/**
	 * @var InstallCommand
	 */
	protected $command;

	/**
	 * @param InstallCommand $command
	 * @param $payload
	 */
	public function __construct(InstallCommand $command, $payload)
	{
		$this->command = $command;
		$this->payload = $payload;

		$this->managementClient = new ManagementClient(config('storyblok.oauth_token'));
	}

	/**
	 * @return void
	 */
	public function handle() {
		// TODO - validate json.....

		$this->getGroups();
		$this->discoverGroup();
		$this->processBlokFields();
		$this->createComponent();
	}

	/**
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function getGroups() {
		$this->componentGroups = collect($this->managementClient->get('spaces/'.config('storyblok.space_id').'/component_groups')->getBody()['component_groups'])->keyBy('name');
	}


	/**
	 * @return void
	 */
	protected function discoverGroup() {
		// see if UUID is known or name needs replacing
		if (!Str::isUuid($this->payload['component']['component_group_uuid'])) {
			if ($uuid = $this->groupUuidFromName($this->payload['component']['component_group_uuid'])) {
				$this->payload['component']['component_group_uuid'] = $uuid;
			} else {
				$this->command->warn('Requested component group ' . $this->payload['component']['component_group_uuid'] . ' does not exist, using in root');

				unset($this->payload['component']['component_group_uuid']);
			}
		}
	}

	/**
	 * @return void
	 */
	protected function processBlokFields() {
		// Bloks fields - set up component group whitelist
		foreach ($this->payload['component']['schema'] as $fieldKey => $field) {
			if ($field['type'] === 'bloks' && array_key_exists('component_group_whitelist', $field)) {
				foreach ($field['component_group_whitelist'] as $groupKey => $group) {
					if ($uuid = $this->groupUuidFromName($group)) {
						$this->payload['component']['schema'][$fieldKey]['component_group_whitelist'][$groupKey] = $uuid;
					} else {
						$this->command->warn('Requested blok component group whitelist ' . $this->payload['component']['component_group_uuid'] . ' does not exist');

						unset($this->payload['component']['component_group_uuid']);
					}
				}
			}
		}
	}

	/**
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function createComponent() {
		$response = $this->managementClient->get('spaces/'.config('storyblok.space_id').'/components/')->getBody();

		if (collect($response['components'])->keyBy('name')->has($this->payload['component']['name'])) {
			$this->command->warn('Component already exists: ' . $this->payload['component']['display_name'] . ' (' .  $this->payload['component']['name'] . ')');
		} else {
			$this->managementClient->post('spaces/'.config('storyblok.space_id').'/components/', $this->payload)->getBody();

			$this->command->info('Created: ' . $this->payload['component']['display_name'] . ' (' .  $this->payload['component']['name'] . ')');
		}
	}

	// TODO - presets in schema such as adding validators for numeric inputs
	protected function processPresets() {

	}

	// TODO
	protected function processTabs() {
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
	}

	/**
	 * @param $name
	 * @return false|mixed
	 */
	protected function groupUuidFromName($name) {
		if ($this->componentGroups->has($name)) {
			return $this->componentGroups[$name]['uuid'];
		}

		return false;
	}
}