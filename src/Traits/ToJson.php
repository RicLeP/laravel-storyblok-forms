<?php

namespace Riclep\StoryblokForms\Traits;

use Illuminate\Support\Collection;

trait ToJson
{
	/**
	 * Converts a field to JSON for VueJS
	 *
	 * @return Collection
	 */
	public function jsonSerialize(): mixed
	{
		// get the validation rules for the field
		$rules = array_map_recursive(function ($rule) {
			return (string) $rule;
		}, $this->validationRules());

		$content = $this->content();
		$content['validators'] = $rules ? $rules[array_key_first($rules)] : [];
		$content['dot_name'] = $this->input_json_dot_name;

		// see if any settings fields have been added to the field
		if ($this->hasSettings()) {
			$content['settings'] = $this->settings();
		}

		// run any field specific additions
		if (method_exists($this, 'addToJson')) {
			$content = $content->merge($this->addToJson());
		}

		return collect([
			'component' => $this->component(),
			'content' => $content,
		]);
	}
}