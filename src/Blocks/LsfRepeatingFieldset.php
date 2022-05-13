<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class LsfRepeatingFieldset extends LsfFieldset
{
	use HasNames, InFieldset, ToJson;

	protected $type = 'fieldset';

	public function response($input) {
		return [
			'label' => $this->label,
			'response' => collect($input)->map(function ($repeatedFields) {
				return $this->fields->map(function ($field) use ($repeatedFields) {
					// Handle empty radio buttons sending nothing in POST request
					if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
						if (!array_key_exists($field->name, $repeatedFields)) {
							$repeatedFields[$field->name] = null;
						}
					}

					return $field->response($repeatedFields[$field->name]);
				});
			}),
			'type' => $this->type,
		];
	}
}