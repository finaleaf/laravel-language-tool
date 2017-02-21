{{--
Created by Fil on 2017-02-08.
--}}

@foreach($langs as $lang_name => $lang)
	<table border="1" style="border-collapse: collapse;" cellpadding="2">
		<tr>
			<td colspan="5"><strong>{{$lang_name}}</strong></td>
		</tr>
		<tr>
			<th>idx1</th>
			<th>idx2</th>
			<th>idx3</th>
			<th>ko</th>
			<th>cn</th>
		</tr>
		@foreach($lang as $key => $depth1)
			@if(is_array($depth1))

				@foreach($depth1 as $key2 => $depth2)
					@if(is_array($depth2))
						@foreach($depth2 as $key3 => $depth3)
							<tr>
								<td>{{$key}}</td>
								<td>{{$key2}}</td>
								<td>{{$key3}}</td>
								<td>{{$depth3}}</td>
								<td>{{trans($lang_name.".".$key.".".$key2.".".$key3, [], "zh")}}</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td>{{$key}}</td>
							<td>{{$key2}}</td>
							<td></td>
							<td>{{$depth2}}</td>
							<td>{{trans($lang_name.".".$key.".".$key2, [], "zh")}}</td>
						</tr>
					@endif

				@endforeach

			@else
				<tr>
					<td>{{$key}}</td>
					<td></td>
					<td></td>
					<td>{{$depth1}}</td>
					<td>{{trans($lang_name.".".$key, [], "zh")}}</td>
				</tr>
			@endif

		@endforeach
	</table>
	<br />
	<br />
	<br />
@endforeach
