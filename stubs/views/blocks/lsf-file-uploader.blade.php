<?php
/** @var \RicLep\StoryblokForms\Blocks\lsfInput $block */
?>

<label>
	<span>{{ $block->label }}</span>

	<input type="file" name="{{ $block->input_name }}" placeholder="{{ $block->placeholder }}" value="{{ data_get(old(), $block->input_dot_name) }}">

	@error($block->input_dot_name)
		<small>{{ $message }}</small>
	@enderror
</label>

@php
	$block->validationRules();
@endphp