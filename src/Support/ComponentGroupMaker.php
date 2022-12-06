<?php

namespace Riclep\StoryblokForms\Support;

use Illuminate\Support\Collection;
use Riclep\StoryblokForms\Console\InstallCommand;
use Riclep\StoryblokForms\Traits\GetsComponentGroups;
use Storyblok\ManagementClient;

class ComponentGroupMaker
{
	// TODO - refactor to use Laravel Storyblok CLI package

	use GetsComponentGroups;

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
	 */
	public function __construct(InstallCommand $command)
	{
		$this->command = $command;

		$this->managementClient = new ManagementClient(config('storyblok.oauth_token'));
	}

	/**
	 * Get the import started
	 *
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	public function import(): void
	{
		$this->getGroups();

		$groups = config('storyblok-forms.component_groups');

		foreach ($groups as $group) {
			$this->createGroup($group);
		}
	}

	/**
	 * Creates a new Component Group in Storyblok
	 *
	 * @param $group
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function createGroup($group): void
	{
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