<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Input;

class LsfInput extends Input
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


	/*
	 * public function response($input) {
		//dd($input, $this);

		return [
			'label' => $this->label,
		//	'response' => $this->response($input[$this->name] ?? ''),
		];
	}
	 *
	 * */
}