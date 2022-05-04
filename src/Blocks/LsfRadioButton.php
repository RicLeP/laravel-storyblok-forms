<?php

namespace Riclep\StoryblokForms\Blocks;

use Riclep\StoryblokForms\MultiInput;

class LsfRadioButton extends MultiInput
{
	/**
	 * @var string The name of the textarea in Storyblok holding the radio button options
	 */
	protected $optionsName = 'radio_buttons';
}