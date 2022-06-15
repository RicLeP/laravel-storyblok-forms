<?php

namespace Riclep\StoryblokForms;

use Riclep\StoryblokForms\Traits\HasNames;
use Riclep\StoryblokForms\Traits\InFieldset;
use Riclep\StoryblokForms\Traits\ToJson;

class Input extends \Riclep\Storyblok\Block
{
	use HasNames, InFieldset, ToJson;

	protected $key;

	/**
	 * @var string[] All the Validators for this Input
	 */
	protected $_casts = ['validators' => Validators::class];

	public function loopKey($key) {
		$this->key = $key;

		return $this;
	}


	/**
	 * All the Validation rules for this Input
	 *
	 * @return mixed
	 */
	public function validationRules() {
		return $this->validators->validationRules();
	}

	/**
	 * All the error messages for this Input
	 *
	 * @return mixed
	 */
	public function errorMessages() {
		return $this->validators->errorMessages();
	}


	/**
	 * Set’s the default Input size when not defined
	 *
	 * @return mixed|string
	 */
	public function getSizeAttribute() {
		if ($this->has('size')) {
			return $this->content()['size'] ?: 'full'; // TODO configure default size
		}

		return 'full'; // TODO configure default size
	}
}