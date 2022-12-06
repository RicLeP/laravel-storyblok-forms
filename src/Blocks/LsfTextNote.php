<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\Storyblok\Block;

class LsfTextNote extends Block

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
		return [];
	}

	/**
	 * Converts a field to JSON for VueJS
	 *
	 * @return Collection
	 */
	public function jsonSerialize(): mixed
	{
		$content = $this->content();
		$content['validators'] = [];
		$content['dot_name'] = Str::random(10);

		return collect([
			'component' => $this->component(),
			'content' => $content,
		]);
	}
}