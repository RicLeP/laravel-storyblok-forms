<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class LsfRepeatingFieldset extends LsfFieldset
{
	use HasNames, InFieldset, ToJson;

	protected $type = 'repeating-fieldset';

	public function response($input) {
		return [
			'label' => $this->label,
			'response' => collect($input)->map(function ($repeatedFields) {
				return $this->fields->map(function ($field) use ($repeatedFields) {

					// Handle empty radio buttons and uncreated repeats (no fields made)
					//if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
						if (!array_key_exists($field->name, $repeatedFields)) {
							$repeatedFields[$field->name] = null;
						}
					//}

					return $field->response($repeatedFields[$field->name]);
				})->toArray();
			})->toArray(),
			'type' => $this->type,
		];
	}
}