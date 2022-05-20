
@php
	//dump($subFields);
@endphp


<table border="1" cellpadding="5">
	<caption>{{ $field['label'] }}</caption>

	@foreach($field['response'] as $subFields)
		<tr>
			@if($subFields['type'] === 'fieldset')
				<td colspan="2">
					@include('emails.form.fields.' . $subFields['type'], ['field' => $subFields])
				</td>
			@else
				@include('emails.form.fields.' . $subFields['type'], ['field' => $subFields])
			@endif
		</tr>
	@endforeach
</table>
