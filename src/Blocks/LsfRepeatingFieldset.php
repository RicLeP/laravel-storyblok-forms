<?php

namespace Riclep\StoryblokForms\Blocks;

class LsfRepeatingFieldset extends LsfFieldset
{
	protected string $type = 'repeating-fieldset';

	/**
	 * Add additional data for the Repeating Fieldset for VueJS
	 *
	 * @return array
	 */
	protected function addToJson(): array
	{
		$json['min'] = $this->min;
		$json['max'] = $this->max;
		$json['item_name'] = $this->item_name ?: 'Item';

		return $json;
	}

	/**
	 * Returns the Fieldset’s response after the form has been submitted and validated
	 *
	 * @param $input
	 * @return array
	 */
	public function response($input): array
	{
		return [
			'label' => $this->label,
			'name' => $this->name,
			'item_name' => $this->item_name === '' ? $this->item_name : 'Item',
			'response' => collect($input)->map(fn($repeatedFields) => $this->fields->map(function ($field) use ($repeatedFields) {

				// Handle empty radio buttons and uncreated repeats (no fields made)
				//if ($field instanceof \Riclep\StoryblokForms\Blocks\LsfRadioButton) {
					if (!array_key_exists($field->name, $repeatedFields)) {
						$repeatedFields[$field->name] = null;
					}
				//}

				return $field->response($repeatedFields[$field->name]);
			})->keyBy('name')->toArray())->toArray(),
			'type' => $this->type,
		];
	}
}