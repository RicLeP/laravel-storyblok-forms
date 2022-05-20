<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;

class LsfCheckbox extends MultiInput
{
	/**
	 * @var string The name of the textarea in Storyblok holding the checkbox options
	 */
	protected $optionsName = 'checkboxes';

	protected $type = 'multi-input';
}