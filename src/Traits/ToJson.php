<?php

namespace Riclep\StoryblokForms\Traits;

use Riclep\StoryblokForms\MultiInput;

trait ToJson
{
	public function jsonSerialize(): mixed
	{
		$rules = $this->validationRules();

		$content = $this->content();
		$content['validators'] = $rules ? $rules[array_key_first($rules)] : [];

		if ($this instanceof MultiInput) {
			$content['options'] = $this->options();
		}

		return collect([
			'component' => $this->component(),
			'content' => $content,
		]);
	}
}