<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;

class LsfCheckbox extends MultiInput
{
	/**
	 * @var string The name of the textarea field in Storyblok holding the checkbox options
	 */
	protected string $optionsName = 'checkboxes';

	protected string $type = 'input';
}