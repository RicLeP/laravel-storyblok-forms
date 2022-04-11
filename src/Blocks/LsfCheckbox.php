<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;
use Riclep\StoryblokForms\Validators;

class LsfCheckbox extends MultiInput
{
	/**
	 * @var string
	 */
	protected $siblingsName = 'checkboxes';

	/**
	 * @var string[]
	 */
	protected $_casts = ['validators' => Validators::class];
}