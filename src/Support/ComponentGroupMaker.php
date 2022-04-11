<?php

namespace Riclep\StoryblokForms\Support;

use Riclep\StoryblokForms\Console\InstallCommand;
use Storyblok\ManagementClient;

class ComponentGroupMaker
{
	/**
	 * @var
	 */
	protected $componentGroups;

	/**
	 * @var InstallCommand
	 */
	protected $command;

	/**
	 * @var ManagementClient
	 */
	protected $managementClient;

	/**
	 * @param InstallCommand $command
	 */
	public function __construct(InstallCommand $command)
	{
		$this->command = $command;

		$this->managementClient = new ManagementClient(config('storyblok.oauth_token'));
	}

	/**
	 * @return void
	 */
	public function handle() {
		$this->getGroups();

		$groups = config('storyblok-forms.component_groups');

		foreach ($groups as $group) {
			$this->createGroup($group);
		}
	}

	/**
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function getGroups() {
		$this->componentGroups = collect($this->managementClient->get('spaces/'.config('storyblok.space_id').'/component_groups')->getBody()['component_groups'])->keyBy('name');
	}

	/**
	 * @param $group
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function createGroup($group) {
		if (!$this->componentGroups->has($group)) {
			$response = $this->managementClient->post('spaces/'.config('storyblok.space_id').'/component_groups', [
				'component_group' =>  [
					'name' => $group
				]
			]);
			$this->componentGroups = $this->componentGroups->merge(collect($response->getBody())->keyBy('name'));

			$this->command->info('Created group: ' . $group);
		} else {
			$this->command->warn($group. 'component group already exists');
		}
	}
}