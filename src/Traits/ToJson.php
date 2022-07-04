<?php

namespace Riclep\StoryblokForms\Traits;

use Riclep\StoryblokForms\Blocks\LsfRepeatingFieldset;
use Riclep\StoryblokForms\MultiInput;

trait ToJson
{
	public function jsonSerialize(): mixed
	{
		$rules = array_map_recursive(function ($rule) {
			return (string) $rule;
		}, $this->validationRules());

		$content = $this->content();
		$content['validators'] = $rules ? $rules[array_key_first($rules)] : [];
		$content['dot_name'] = $this->input_json_dot_name;

		if ($this->hasSettings()) {
			$content['settings'] = $this->settings();
		}

		if (method_exists($this, 'addToJson')) {
			$content = $content->merge($this->addToJson());
		}

		return collect([
			'component' => $this->component(),
			'content' => $content,
		]);
	}
}