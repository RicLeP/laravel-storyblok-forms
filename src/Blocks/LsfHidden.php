<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Input;

class LsfHidden extends Input
{
	// Interface this....

	protected string $type = 'input';

	/**
	 * All the Validation rules for this Input
	 *
	 * @return mixed
	 */
	public function errorMessages(): mixed
	{
		return [];
	}

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