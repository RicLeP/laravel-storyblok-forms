<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Input;

class LsfFileUploader extends Input
{
	protected string $type = 'upload';

	/**
	 * Returns the Input’s response after the form has been submitted and validated
	 *
	 * @param $input
	 * @return array
	 */
	public function response($input): array
	{
		return [
			'label' => $this->label,
			'name' => $this->name,
			'response' => $input,
			'type' => $this->type,
		];
	}
}