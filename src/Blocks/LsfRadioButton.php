<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;

class LsfRadioButton extends MultiInput
{
	/**
	 * @var string The name of the textarea field in Storyblok holding the radio button options
	 */
	protected string $optionsName = 'radio_buttons';

	protected string $type = 'multi-input';
}