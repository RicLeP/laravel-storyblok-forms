<?php

namespace Riclep\StoryblokForms\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Riclep\StoryblokForms\Console\InstallCommand;
use Riclep\StoryblokForms\Traits\GetsComponentGroups;
use Storyblok\ManagementClient;

class ComponentMaker
{
	use GetsComponentGroups;

	/**
	 * @var array Component definition
	 */
	protected $schema;

	/**
	 * @var Collection A list of all component groups
	 */
	protected $componentGroups;

	/**
	 * @var ManagementClient Storyblok Management Client
	 */
	protected $managementClient;

	/**
	 * @var InstallCommand The package’s command
	 */
	protected $command;

	/**
	 * @param InstallCommand $command
	 * @param $schema
	 */
	public function __construct(InstallCommand $command, $schema)
	{
		$this->command = $command;
		$this->schema = $schema;

		$this->managementClient = new ManagementClient(config('storyblok.oauth_token'));
	}

	/**
	 * Get the import started
	 *
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	public function import() {
		// TODO - validate json.....

		$this->getGroups();
		$this->updatedGroupToUuid();
		$this->processBlokFields();
		$this->createComponent();
	}

	/**
	 * Takes a ‘named group’ from a $schema and replaces it with the Storyblok group UUID
	 *
	 * @return void
	 */
	protected function updatedGroupToUuid() {
		// see if UUID is known or name needs replacing
		if (!Str::isUuid($this->schema['component']['component_group_uuid'])) {
			if ($uuid = $this->groupUuidFromName($this->schema['component']['component_group_uuid'])) {
				$this->schema['component']['component_group_uuid'] = $uuid;
			} else {
				$this->command->warn('Requested component group ' . $this->schema['component']['component_group_uuid'] . ' does not exist, using in root');

				unset($this->schema['component']['component_group_uuid']);
			}
		}
	}

	/**
	 * Parses a schema’s fields to see if they need any processing.
	 * Blok fields will have their whitelist and other settings configured
	 *
	 * @return void
	 */
	protected function processBlokFields() {
		// Bloks fields - set up component group whitelist
		foreach ($this->schema['component']['schema'] as $fieldKey => $field) {
			if ($field['type'] === 'bloks' && array_key_exists('component_group_whitelist', $field)) {
				foreach ($field['component_group_whitelist'] as $groupKey => $group) {
					if ($uuid = $this->groupUuidFromName($group)) {
						$this->schema['component']['schema'][$fieldKey]['component_group_whitelist'][$groupKey] = $uuid;
					} else {
						$this->command->warn('Requested blok component group whitelist ' . $this->schema['component']['component_group_uuid'] . ' does not exist');

						unset($this->schema['component']['component_group_uuid']);
					}
				}
			}
		}
	}

	/**
	 * Creates the component within Storyblok
	 *
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function createComponent() {
		$response = $this->managementClient->get('spaces/'.config('storyblok.space_id').'/components/')->getBody();

		if (collect($response['components'])->keyBy('name')->has($this->schema['component']['name'])) {
			$this->command->warn('Component already exists: ' . $this->schema['component']['display_name'] . ' (' .  $this->schema['component']['name'] . ')');
		} else {
			$this->managementClient->post('spaces/'.config('storyblok.space_id').'/components/', $this->schema)->getBody();

			$this->command->info('Created: ' . $this->schema['component']['display_name'] . ' (' .  $this->schema['component']['name'] . ')');
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
	 * Takes a component group’s name and returns the Storyblok UUID for that group
	 *
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