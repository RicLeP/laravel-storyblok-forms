<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfRepeatingFieldset extends LsfFieldset
{
	protected $type = 'repeating-fieldset';

	protected function addToJson() {
		$json['min'] = $this->min;
		$json['max'] = $this->max;

		return $json;
	}

	public function response($input) {
		return [
			'label' => $this->label,
			'name' => $this->name,
			'item_name' => $this->item_name ?? 'Item',
			'response' => collect($input)->map(function ($repeatedFields) {
				return $this->fields->map(function ($field) use ($repeatedFields) {

					// Handle empty radio buttons and uncreated repeats (no fields made)
					//if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
						if (!array_key_exists($field->name, $repeatedFields)) {
							$repeatedFields[$field->name] = null;
						}
					//}

					return $field->response($repeatedFields[$field->name]);
				})->keyBy('name')->toArray();
			})->toArray(),
			'type' => $this->type,
		];
	}
}