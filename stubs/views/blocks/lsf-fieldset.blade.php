<?php
/** @var \RicLep\StoryblokForms\Blocks\LsfFieldset $block */
?>

<p>{{ $block->title }}</p>

@foreach($block->fields as $field)
    {{ $field->render() }}
@endforeach