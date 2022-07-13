<?php

namespace App\Storyblok\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Blocks\LsfConditionalSelect;
use Riclep\StoryblokForms\Input;

class LsfAddress extends Input
{
	// Interface this....

	protected $type = 'address';

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