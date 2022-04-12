<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfSelect $block */
?>

<label>
	<p>{{ $block->label }}</p>

	<select name="{{ $block->name }}">
		@if ($block->show_empty_option && !$block->placeholder)
			<option value=""></option>
		@endif

		@if ($block->placeholder)
			<option value="">{{ $block->placeholder }}</option>
		@endif

		@foreach($block->options() as $option)
			<option value="{{ Str::slug($option['value']) }}" @if ($option['selected']) selected @endif>
				{{ $option['label'] }}
			</option>
		@endforeach
	</select>

	@error($block->name )
		<small>{{ $message }}</small>
	@enderror
</label>