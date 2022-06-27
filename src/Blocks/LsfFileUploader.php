<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Input;

class LsfFileUploader extends Input
{
	protected $type = 'upload';

	/**
	 * Returns the Input’s response after the form has been submitted and validated
	 *
	 * @param $input
	 * @return mixed
	 */
	public function response($input) {
		return [
			'label' => $this->label,
			'name' => $this->name,
			'response' => $input,
			'type' => $this->type,
		];
	}
}