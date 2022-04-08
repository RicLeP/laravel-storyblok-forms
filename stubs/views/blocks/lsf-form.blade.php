<form method="POST" action="/">
	@csrf

	<input type="hidden" name="_slug" value="{{ $block->page()->meta('slug') }}">

	@foreach($block->fields as $field)
		{{ $field->render() }}
	@endforeach

	<button>Submit</button>
</form>