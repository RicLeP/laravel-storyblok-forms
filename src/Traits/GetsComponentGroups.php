<?php

namespace Riclep\StoryblokForms\Traits;

trait GetsComponentGroups
{
	/**
	 * Request all the component groups from Storyblok
	 *
	 * @return void
	 * @throws \Storyblok\ApiException
	 */
	protected function getGroups() {
		$this->componentGroups = collect($this->managementClient->get('spaces/'.config('storyblok.space_id').'/component_groups')->getBody()['component_groups'])->keyBy('name');
	}
}