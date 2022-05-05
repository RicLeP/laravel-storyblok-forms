<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Input;

class LsfTextarea extends Input
{
	// Interface this....

	/**
	 * Returns the Input’s response after the form has been submitted and validated
	 *
	 * @param $input
	 * @return mixed
	 */
	public function response($input) {
		return $input;
	}
}