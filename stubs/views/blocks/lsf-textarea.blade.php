<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfTextarea $block */
?>

<label>
	<span>{{ $block->label }}</span>

	<textarea name="{{ $block->name }}" placeholder="{{ $block->placeholder }}">{{ data_get(old(), $block->input_dot_name) }}</textarea>

	@error($block->input_dot_name)
		<small>{{ $message }}</small>
	@enderror
</label>