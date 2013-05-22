@layout('layouts/default')

@section('content')

<div class="row padded">
	
	<!-- toolbar -->
	<div class="btn-toolbar">

		{{ Form::open('ticket/search', 'PUT', array('id' => 'form-status', 'class' => 'hidden')) }}
		{{ Form::close() }}

		{{-- for search form --}}
		{{ Form::open('ticket/search', 'PUT', array('id' => 'form-id')) }}

			<div class="btn-group">

				<button type="submit" class="btn" name="value" value="status|closed" form="form-status">{{ Helper::icon('ok') }} Cerradas</button>
				<button type="submit" class="btn" name="value" value="status|open" form="form-status">{{ Helper::icon('exclamation') }} Abiertas</button>
				<button type="submit" class="btn" name="value" value="status|hold" form="form-status">{{ Helper::icon('time') }} En espera</button>
				<button type="submit" class="btn" name="value" value="status|in-progress" form="form-status">{{ Helper::icon('star-half-empty') }} En proceso</button>
				<a href="{{ URL::to('tickets') }}" class="btn">{{ Helper::icon('list') }} Mostrar todas</a>

			</div>

			<div class="input-append pull-right">

				<input type="text" name="value" value="" placeholder="Consulta #" />
				<button type="submit" class="btn btn-primary" name="type" value="id">{{ Helper::icon('search') }}</button>

			</div>

		{{ Form::close() }}

	</div>

	<!-- end toolbar -->

	{{-- tickets found, create table --}}
	@if (!empty($tickets))

		<!-- tickets -->
		<table class="table table-striped table-hover table-bordered table-tickets">

			<!-- tickets head row -->
			<thead>

				<tr>

					<th>#</th>
					<th>Consulta</th>
					<th>Reportado por</th>
					<th>Asignado a</th>
					<th>Estatus</th>

				</tr>

			</thead>
			<!-- end tickets head row -->

			<!-- all found tickets -->
			<tbody>

				@foreach($tickets->results as $ticket)

					<?php 
						foreach($users as $user) {
							if ($user->id == $ticket->reported_by) {
								$reported = $user;
								$reported->name = $reported->firstname . ' ' . $reported->lastname;
							}
						}

						// to prevent conflicts with next loop
						unset($user);

						if (!empty($ticket->assigned_to)) {
							foreach($users as $user) {
								if ($user->id == $ticket->assigned_to) {
									$assigned = $user;
									$assigned->name = $assigned->firstname . ' ' . $assigned->lastname;
								}
							}
						} else {
								$assigned = new StdClass;
								$assigned->name = '<span class="muted">Nadie</span>';
						}

						// for consistency
						unset($user);

						switch($ticket->status) {
							case 'open':			$type = 'warning'; break;
							case 'hold':			$type = 'info'; break;
							case 'closed':			$type = ''; break;
							case 'in-progress':	$type = 'default'; break;
						}
					?>

					<!-- ticket row -->
					<tr class="{{ $type }}">

						<td>{{ $ticket->id }}</td>

						<td>

							<p>{{ HTML::link('ticket/' . $ticket->id, $ticket->subject) }}</p>
							<small><strong>Creado:</strong>{{ $ticket->created_at }}</small><br />
							<small><strong>Última actualización:</strong> {{ $ticket->updated_at }}</small>

						</td>

						<td>{{ $reported->name }}</td>
						<td>{{ $assigned->name }}</td>
						<td>{{ Helper::status($ticket->status) }}</td>

					</tr>
					<!-- end ticket row -->
				@endforeach

			</tbody>
			<!-- end all tickets -->

		</table>
		<!-- end tickets table -->

		{{ $tickets->links() }}

		{{-- if no tickets are found or they don't match any query --}}
		@else

			<div class="alert">

				<span class="block center">No existen consultas</span>

			</div>

		@endif

</div>

@endsection