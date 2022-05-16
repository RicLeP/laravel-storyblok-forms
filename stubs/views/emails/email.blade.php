@php
//	dd($fields);
@endphp


@foreach($fields as $subFields)
	@include('emails.form.fields.' . $subFields['type'], ['field' => $subFields])
@endforeach