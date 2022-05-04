<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Input;

class LsfTextarea extends Input
{
	/**
	 * Returns the name attribute used for the Inout’s HTML tag in the correct format
	 *
	 * @return string
	 */
	public function getNameAttribute() {
		return Str::slug($this->content()['name']);
	}


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