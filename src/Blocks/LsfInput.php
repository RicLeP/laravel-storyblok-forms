<?php

namespace Riclep\StoryblokForms\Blocks;

use Illuminate\Support\Str;
use Riclep\StoryblokForms\Input;
use Riclep\StoryblokForms\Validators;

class LsfInput extends Input
{
	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];

	/**
	 * @return string
	 */
	public function getNameAttribute() {
		return Str::slug($this->content()['name']);
	}



	// Interface this....

	/**
	 * @param $input
	 * @return mixed
	 */
	public function response($input) {
		return $input;
	}
}