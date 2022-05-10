<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfInput $block */
?>

<label>
	<span>{{ $block->label }}</span>

	<input type="{{ $block->type }}" name="{{ $block->name }}" placeholder="{{ $block->placeholder }}" value="{{ old($block->input_dot_name) }}">

	@error($block->input_dot_name)
		<small>{{ $message }}</small>
	@enderror
</label>