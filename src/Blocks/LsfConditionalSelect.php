<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;
use Riclep\StoryblokForms\Traits\HasNames;

class LsfConditionalSelect extends MultiInput
{
	use HasNames;

	/**
	 * @var string
	 */
	protected $optionsName = 'options';

	protected $type = 'multi-input';
}