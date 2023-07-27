@extends('layouts.app')
@section('content')
<div class="table-responsive col-auto">
	<table class="table table-hover table-sm">
		<tbody>
			<tr>
				<td rowspan="3" class="text-danger col-sm-3">Attention :</td>
				<td>Leave application must be at least <span class="font-weight-bold">THREE (3)</span> days in advance for <strong>"Annual Leave"</strong> and <strong>"Unpaid Leave"</strong>. Otherwise it will be considered as <strong>"Emergency Annual Leave"</strong> or <strong>"Emergency Unpaid Leave"</strong></td>
			</tr>
			<tr>
				<td><strong>"Time-Off"</strong> will consider as a <strong>"Leave"</strong>, if leave period exceed <strong>more than 2 hours</strong>.</td>
			</tr>
			<tr>
				<td>Application for <strong>"Sick Leave/Medical Certificate (MC)"</strong> or <strong>"Unpaid Medical Certificate (MC-UPL)"</strong> will only be <strong>considered VALID and ELIGIBLE</strong> if a sick/medical certificate is <strong>issued by a REGISTERED government hospital/clinic or panel clinic only.</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- herecomes the hardest part, leave application -->

<div class="col-auto row justify-content-center">
	{{ Form::open(['route' => ['leave.store'], 'id' => 'form', 'autocomplete' => 'off', 'files' => true,  'data-toggle' => 'validator']) }}
	<h5>Leave Application</h5>

	<div class="form-group row {{ $errors->has('leave_id') ? 'has-error' : '' }}">
		{{ Form::label( 'leave_id', 'Leave Type : ', ['class' => 'col-sm-2 col-form-label'] ) }}
		<div class="col-sm-4">
			<select name="leave_id" id="leave_id" class="form-control col-auto"></select>
		</div>
	</div>

	<div class="form-group row mb-3 {{ $errors->has('reason') ? 'has-error' : '' }}">
		{{ Form::label( 'reason', 'Reason : ', ['class' => 'col-sm-2 col-form-label'] ) }}
		<div class="col-auto">
			{{ Form::textarea('reason', @$value, ['class' => 'form-control col-auto', 'id' => 'reason', 'placeholder' => 'Sebab Cuti', 'autocomplete' => 'off']) }}
		</div>
	</div>

	<div id="wrapper"></div>

	<div class="form-group row mb-3 {{ $errors->has('akuan') ? 'has-error' : '' }}">
		<p class="col-sm-2 col-form-label">Acknowledgement :</p>
		<div class="col-auto form-check">
			{{ Form::checkbox('akuan', 1, @$value, ['class' => 'form-check-input ', 'id' => 'akuan1']) }}
				<label for="akuan1" class="form-check-label p-3 mb-3 bg-warning text-danger rounded">I hereby confirmed that all details and information filled in are <strong>CORRECT</strong> and <strong>CHECKED</strong> before sending.</label>
		</div>
	</div>

	<div class="form-group row mb-3">
		<div class="col-sm-10 offset-sm-2">
			{!! Form::button('Save Application', ['class' => 'btn btn-sm btn-outline-secondary', 'type' => 'submit']) !!}
		</div>
	</div>
	{{ Form::close() }}
</div>


@endsection
@section('js')
/////////////////////////////////////////////////////////////////////////////////////////
$('#leave_id').select2({
	placeholder: 'Please choose',
	ajax: {
		url: '{{ route('leaveType.leaveType') }}',
		// data: { '_token': '{!! csrf_token() !!}' },
		type: 'POST',
		dataType: 'json',
		data: function (params) {
			var data = {
				id: {{ \Auth::user()->belongstostaff->id }},
				_token: '{!! csrf_token() !!}',
			}
			// Query parameters will be ?search=[term]&_token=67y0VEKOi0SnS3HBcEHR0qOv10rO1l9fn82ovUWD
			return data;
		}
	},
	allowClear: true,
	closeOnSelect: true,
	width: '100%',
});

/////////////////////////////////////////////////////////////////////////////////////////
//enable ckeditor
// its working, i just disable it
// $(document).ready(function() {
// 	var editor = CKEDITOR.replace( 'reason', {});
// 	// editor is object of your CKEDITOR
// 	editor.on('change',function(){
// 	     // console.log();
// 	    $('#form').bootstrapValidator('revalidateField', 'reason');
// 	});
// });
// // with jquery adapter
// $('textarea#reason').ckeditor();

/////////////////////////////////////////////////////////////////////////////////////////
// start setting up the leave accordingly.
<?php
$user = \Auth::user()->belongstostaff;
$userneedbackup = $user->belongstoleaveapprovalflow->backup_approval;
?>

/////////////////////////////////////////////////////////////////////////////////////////
//  global variable : ajax to get the unavailable date
var data2 = $.ajax({
	url: "{{ route('leavedate.unavailabledate') }}",
	type: "POST",
	data : {
				id: {{ \Auth::user()->belongstostaff->id }},
				_token: '{!! csrf_token() !!}',
			},
	dataType: 'json',
	global: false,
	async:false,
	success: function (response) {
		// you will get response from your php page (what you echo or print)
		// return response;
		var arrItems = [];              		// The array to store JSON data.
		$.each(response, function (index, value) {
			arrItems.push(value);       		// Push the value inside the array.
		});
		return arrItems;
	},
	error: function(jqXHR, textStatus, errorThrown) {
		console.log(textStatus, errorThrown);
	}
}).responseText;

// this is how u cange from json to array type data
var data = $.parseJSON( data2 );

/////////////////////////////////////////////////////////////////////////////////////////
// start here when user start to select the option
$('#leave_id').on('change', function() {
	$selection = $(this).find(':selected');
	// console.log($selection.val());

	// annual leave & UPL
	if ($selection.val() == '1' || $selection.val() == '3') {

		$('#form').bootstrapValidator('removeField', $('.datetime').find('[name="date_time_start"]'));
		$('#form').bootstrapValidator('removeField', $('.datetime').find('[name="date_time_end"]'));
		$('#form').bootstrapValidator('removeField', $('.time').find('[name="time_start"]'));
		$('#form').bootstrapValidator('removeField', $('.time').find('[name="time_end"]'));
		$('#form').bootstrapValidator('removeField', $('.backup').find('[name="staff_id"]'));
		$('#form').bootstrapValidator('removeField', $('.supportdoc').find('[name="document"]'));
		$('#form').bootstrapValidator('removeField', $('.suppdoc').find('[name="documentsupport"]'));

		$('#remove').remove();
		$('#wrapper').append(

			'<div id="remove">' +
				<!-- annual leave -->

				'<div class="form-group row mb-3 {{ $errors->has('date_time_start') ? 'has-error' : '' }}">' +
					'{{ Form::label('from', 'From : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-sm-10 datetime" style="position: relative">' +
						'{{ Form::text('date_time_start', @$value, ['class' => 'form-control col-auto', 'id' => 'from', 'placeholder' => 'From : ', 'autocomplete' => 'off']) }}' +
					'</div>' +
				'</div>' +

				'<div class="form-group row mb-3 {{ $errors->has('date_time_end') ? 'has-error' : '' }}">' +
					'{{ Form::label('to', 'To : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-sm-10 datetime" style="position: relative">' +
						'{{ Form::text('date_time_end', @$value, ['class' => 'form-control col-auto', 'id' => 'to', 'placeholder' => 'To : ', 'autocomplete' => 'off']) }}' +
					'</div>' +
				'</div>' +

				'<div class="form-group row mb-3 {{ $errors->has('leave_type') ? 'has-error' : '' }}" id="wrapperday">' +
					'{{ Form::label('leave_type', 'Jenis Cuti : ', ['class' => 'col-sm-2 col-form-label removehalfleave']) }}' +
					'<div class="col-sm-10 row removehalfleave" id="halfleave">' +
						'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="removeleavehalf">' +
							'{{ Form::radio('leave_type', '1', true, ['id' => 'radio1', 'class' => 'form-check-input removehalfleave']) }}' +
							'<div class="state p-success removehalfleave">' +
								'{{ Form::label('radio1', 'Cuti Penuh', ['class' => 'form-check-label removehalfleave']) }}' +
							'</div>' +
						'</div>' +
						'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="appendleavehalf">' +
							'{{ Form::radio('leave_type', '2', NULL, ['id' => 'radio2', 'class' => 'form-check-input removehalfleave']) }}' +
							'<div class="state p-success removehalfleave">' +
								'{{ Form::label('radio2', 'Cuti Separuh', ['class' => 'form-check-label removehalfleave']) }}' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="form-group row col-sm-10 offset-sm-2 {{ $errors->has('leave_half') ? 'has-error' : '' }} removehalfleave"  id="wrappertest">' +
					'</div>' +
				'</div>' +

		@if( $userneedbackup == 1 )
				'<div class="form-group row mb-3 {{ $errors->has('staff_id') ? 'has-error' : '' }}">' +
					'{{ Form::label('backupperson', 'Backup Person : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-auto backup">' +
						'<select name="staff_id" id="backupperson" class="form-control form-select form-select-sm " placeholder="Please choose" autocomplete="off"></select>' +
					'</div>' +
				'</div>' +
		@endif
			'</div>'
			);
		/////////////////////////////////////////////////////////////////////////////////////////
		// add more option
		//add bootstrapvalidator
		$('#form').bootstrapValidator('addField', $('.datetime').find('[name="date_time_start"]'));
		$('#form').bootstrapValidator('addField', $('.datetime').find('[name="date_time_end"]'));
		@if( $userneedbackup == 1 )
		$('#form').bootstrapValidator('addField', $('.backup').find('[name="staff_id"]'));
		@endif

		/////////////////////////////////////////////////////////////////////////////////////////
		//enable select 2 for backup
		$('#backupperson').select2({
			placeholder: 'Please Choose',
			width: '100%',
			ajax: {
				url: '{{ route('backupperson.backupperson') }}',
				// data: { '_token': '{!! csrf_token() !!}' },
				type: 'POST',
				dataType: 'json',
				data: function (params) {
					var query = {
						id: {{ \Auth::user()->belongstostaff->id }},
						_token: '{!! csrf_token() !!}',
					}
					return query;
				}
			},
			allowClear: true,
			closeOnSelect: true,
		});

		/////////////////////////////////////////////////////////////////////////////////////////
		// start date
		$('#from').datetimepicker({
			icons: {
				time: "fas fas-regular fa-clock fa-beat",
				date: "fas fas-regular fa-calendar fa-beat",
				up: "fas fas-regular fa-arrow-up fa-beat",
				down: "fas fas-regular fa-arrow-down fa-beat",
				previous: 'fas fas-regular fa-arrow-left fa-beat',
				next: 'fas fas-regular fa-arrow-right fa-beat',
				today: 'fas fas-regular fa-calenday-day fa-beat',
				clear: 'fas fas-regular fa-broom-wide fa-beat',
				close: 'fas fas-regular fa-rectangle-xmark fa-beat'
			},
			format:'YYYY-MM-DD',
			useCurrent: false,
			minDate: moment().format('YYYY-MM-DD'),
			disabledDates: data,
			// daysOfWeekDisabled: [0],
			// minDate: data[1],
		})
		.on('dp.change dp.show dp.update', function(e) {
			$('#form').bootstrapValidator('revalidateField', 'date_time_start');
			var minDaten = $('#from').val();
			console.log(minDaten);
			$('#to').datetimepicker('minDate', minDaten);

			if($('#from').val() === $('#to').val()) {
				if( $('.removehalfleave').length === 0) {
					$('#wrapperday').append(
							'{{ Form::label('leave_type', 'Jenis Cuti : ', ['class' => 'col-sm-2 col-form-label removehalfleave']) }}' +
							'<div class="col-sm-10 mb-3 removehalfleave row" id="halfleave">' +
								'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="removeleavehalf">' +
									'{{ Form::radio('leave_type', '1', true, ['id' => 'radio1', 'class' => ' removehalfleave']) }}' +
									'<div class="state p-success removehalfleave">' +
										'{{ Form::label('radio1', 'Cuti Penuh', ['class' => 'form-check-label removehalfleave']) }}' +
									'</div>' +
								'</div>' +
								'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="appendleavehalf">' +
									'{{ Form::radio('leave_type', '2', NULL, ['id' => 'radio2', 'class' => ' removehalfleave']) }}' +
									'<div class="state p-success removehalfleave">' +
										'{{ Form::label('radio2', 'Cuti Separuh', ['class' => 'form-check-label removehalfleave']) }}' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="form-group row col-sm-10 offset-sm-2 {{ $errors->has('leave_half') ? 'has-error' : '' }} removehalfleave"  id="wrappertest">' +
							'</div>'
					);
				}
			}
			if($('#from').val() !== $('#to').val()) {
				$('.removehalfleave').remove();
			}
		});

		$('#to').datetimepicker({
			icons: {
				time: "fas fas-regular fa-clock fa-beat",
				date: "fas fas-regular fa-calendar fa-beat",
				up: "fas fas-regular fa-arrow-up fa-beat",
				down: "fas fas-regular fa-arrow-down fa-beat",
				previous: 'fas fas-regular fa-arrow-left fa-beat',
				next: 'fas fas-regular fa-arrow-right fa-beat',
				today: 'fas fas-regular fa-calenday-day fa-beat',
				clear: 'fas fas-regular fa-broom-wide fa-beat',
				close: 'fas fas-regular fa-rectangle-xmark fa-beat'
			},
			format:'YYYY-MM-DD',
			useCurrent: false,
			minDate: moment().format('YYYY-MM-DD'),
			disabledDates:data,
			//daysOfWeekDisabled: [0],
		})
		.on('dp.change dp.show dp.update', function(e) {
			$('#form').bootstrapValidator('revalidateField', 'date_time_end');
			var maxDate = $('#to').val();
			$('#from').datetimepicker('maxDate', maxDate);
			if($('#from').val() === $('#to').val()) {
				if( $('.removehalfleave').length === 0) {
					$('#wrapperday').append(
							'{{ Form::label('leave_type', 'Jenis Cuti : ', ['class' => 'col-sm-2 col-form-label removehalfleave']) }}' +
							'<div class="col-sm-10 mb-3 removehalfleave" id="halfleave">' +
								'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="removeleavehalf">' +
									'{{ Form::radio('leave_type', '1', true, ['id' => 'radio1', 'class' => ' removehalfleave']) }}' +
									'<div class="state p-success removehalfleave">' +
										'{{ Form::label('radio1', 'Cuti Penuh', ['class' => 'form-check-label removehalfleave']) }}' +
									'</div>' +
								'</div>' +
								'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="appendleavehalf">' +
									'{{ Form::radio('leave_type', '2', NULL, ['id' => 'radio2', 'class' => ' removehalfleave']) }}' +
									'<div class="state p-success removehalfleave">' +
										'{{ Form::label('radio2', 'Cuti Separuh Hari', ['class' => 'form-check-label removehalfleave']) }}' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="form-group row col-sm-10 offset-sm-2 {{ $errors->has('leave_half') ? 'has-error' : '' }} removehalfleave"  id="wrappertest">' +
							'</div>'
					);
				}
			}
			if($('#from').val() !== $('#to').val()) {
				$('.removehalfleave').remove();
			}
		});
		// end date

		/////////////////////////////////////////////////////////////////////////////////////////
		// enable radio
		$(document).on('change', '#appendleavehalf :radio', function () {
			if (this.checked) {
				var daynow = moment($('#from').val(), 'YYYY-MM-DD').format('dddd');
				var datenow =$('#from').val();
		
				var data1 = $.ajax({
					url: "{{ route('leavedate.timeleave') }}",
					type: "POST",
					data: {date: datenow, _token: '{!! csrf_token() !!}', id: {{ \Auth::user()->belongstostaff->id }} },
					dataType: 'json',
					global: false,
					async:false,
					success: function (response) {
						// you will get response from your php page (what you echo or print)
						return response;
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				}).responseText;

				// convert data1 into json
				var obj = $.parseJSON( data1 );

				// checking so there is no double
				if( $('.removetest').length == 0 ) {
					$('#wrappertest').append(
						'<div class="pretty p-default p-curve form-check form-check-inline removetest">' +
							'<input type="radio" name="leave_half" value="' + obj.start_am + '/' + obj.end_am + '" id="am" checked="checked">' +
							'<div class="state p-primary">' +
								'<label for="am" class="form-check-label">' + moment(obj.start_am, 'HH:mm:ss').format('h:mm a') + ' to ' + moment(obj.end_am, 'HH:mm:ss').format('h:mm a') + '</label> ' +
							'</div>' +
						'</div>' +
						'<div class="pretty p-default p-curve form-check form-check-inline removetest">' +
							'<input type="radio" name="leave_half" value="' + obj.start_pm + '/' + obj.end_pm + '" id="pm">' +
							'<div class="state p-primary">' +
								'<label for="pm" class="form-check-label">' + moment(obj.start_pm, 'HH:mm:ss').format('h:mm a') + ' to ' + moment(obj.end_pm, 'HH:mm:ss').format('h:mm a') + '</label> ' +
							'</div>' +
						'</div>'
					);
				}
			}
		});

		$(document).on('change', '#removeleavehalf :radio', function () {
		//$('#removeleavehalf :radio').change(function() {
			if (this.checked) {
				$('.removetest').remove();
			}
		});
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($selection.val() == '2') {

		$('#form').bootstrapValidator('removeField', $('.datetime').find('[name="date_time_start"]'));
		$('#form').bootstrapValidator('removeField', $('.datetime').find('[name="date_time_end"]'));
		$('#form').bootstrapValidator('removeField', $('.time').find('[name="time_start"]'));
		$('#form').bootstrapValidator('removeField', $('.time').find('[name="time_end"]'));
		$('#form').bootstrapValidator('removeField', $('.backup').find('[name="staff_id"]'));
		$('#form').bootstrapValidator('removeField', $('.supportdoc').find('[name="document"]'));
		$('#form').bootstrapValidator('removeField', $('.suppdoc').find('[name="documentsupport"]'));

		$('#remove').remove();
		$('#wrapper').append(
			'<div id="remove">' +
				<!-- mc leave -->
				'<div class="form-group row mb-3 {{ $errors->has('date_time_start') ? 'has-error' : '' }}">' +
					'{{ Form::label('from', 'From : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-auto datetime">' +
						'{{ Form::text('date_time_start', @$value, ['class' => 'form-control', 'id' => 'from', 'placeholder' => 'From : ', 'autocomplete' => 'off']) }}' +
					'</div>' +
				'</div>' +

				'<div class="form-group row mb-3 {{ $errors->has('date_time_end') ? 'has-error' : '' }}">' +
					'{{ Form::label('to', 'To : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-auto datetime">' +
						'{{ Form::text('date_time_end', @$value, ['class' => 'form-control', 'id' => 'to', 'placeholder' => 'To : ', 'autocomplete' => 'off']) }}' +
					'</div>' +
				'</div>' +


				'<div class="form-group row mb-3 {{ $errors->has('document') ? 'has-error' : '' }}">' +
					'{{ Form::label( 'doc', 'Upload Supporting Document : ', ['class' => 'col-sm-2 col-form-label'] ) }}' +
					'<div class="col-auto supportdoc">' +
						'{{ Form::file( 'document', ['class' => 'form-control form-control-file', 'id' => 'doc', 'placeholder' => 'Supporting Document']) }}' +
					'</div>' +
				'</div>' +

				'<div class="form-group row mb-3 {{ $errors->has('akuan') ? 'has-error' : '' }}">' +
					'<p class="col-sm-2 col-form-label">Supporting Documents :</p>' +
					'<div class="col-auto form-check suppdoc">' +
						'{{ Form::checkbox('documentsupport', 1, @$value, ['class' => 'form-check-input ', 'id' => 'suppdoc']) }}' +
						'<label for="suppdoc" class="form-check-label p-3 mb-2 bg-warning text-danger rounded">Please ensure you will send <strong>Supporting Documents</strong> within <strong>3 Days</strong> after date leave.</label>' +
					'</div>' +
				'</div>' +

			'</div>'
		);

		//add bootstrapvalidator
		$('#form').bootstrapValidator('addField', $('.datetime').find('[name="date_time_start"]'));
		$('#form').bootstrapValidator('addField', $('.datetime').find('[name="date_time_end"]'));
		$('#form').bootstrapValidator('addField', $('.supportdoc').find('[name="document"]'));
		$('#form').bootstrapValidator('addField', $('.suppdoc').find('[name="documentsupport"]'));

		/////////////////////////////////////////////////////////////////////////////////////////
		// enable datetime for the 1st one
		$('#from').datetimepicker({
			icons: {
				time: "fas fas-regular fa-clock fa-beat",
				date: "fas fas-regular fa-calendar fa-beat",
				up: "fas fas-regular fa-arrow-up fa-beat",
				down: "fas fas-regular fa-arrow-down fa-beat",
				previous: 'fas fas-regular fa-arrow-left fa-beat',
				next: 'fas fas-regular fa-arrow-right fa-beat',
				today: 'fas fas-regular fa-calenday-day fa-beat',
				clear: 'fas fas-regular fa-broom-wide fa-beat',
				close: 'fas fas-regular fa-rectangle-xmark fa-beat'
			},
			format:'YYYY-MM-DD',
			useCurrent: true,
			// minDate: moment().format('YYYY-MM-DD'),
			// disabledDates: data,
			// daysOfWeekDisabled: [0],
			// minDate: data[1],
		})
		.on('dp.change dp.show dp.update', function(e) {
			$('#form').bootstrapValidator('revalidateField', 'date_time_start');
			var minDate = $('#from').val();
			$('#to').datetimepicker('minDate', minDate);
		});
		
		$('#to').datetimepicker({
			icons: {
				time: "fas fas-regular fa-clock fa-beat",
				date: "fas fas-regular fa-calendar fa-beat",
				up: "fas fas-regular fa-arrow-up fa-beat",
				down: "fas fas-regular fa-arrow-down fa-beat",
				previous: 'fas fas-regular fa-arrow-left fa-beat',
				next: 'fas fas-regular fa-arrow-right fa-beat',
				today: 'fas fas-regular fa-calenday-day fa-beat',
				clear: 'fas fas-regular fa-broom-wide fa-beat',
				close: 'fas fas-regular fa-rectangle-xmark fa-beat'
			},
			format:'YYYY-MM-DD',
			useCurrent: true,
			// minDate: moment().format('YYYY-MM-DD'),
			// disabledDates: data,
			// daysOfWeekDisabled: [0],
			// minDate: data[1],
		})
		.on('dp.change dp.show dp.update', function(e) {
			$('#form').bootstrapValidator('revalidateField', 'date_time_end');
			var maxDate = $('#to').val();
			$('#from').datetimepicker('maxDate', maxDate);
		});
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
<?php
$oi = \Auth::user()->belongstostaff->hasmanyleavereplacement()->where('leave_balance', '<>', 0)->get();
?>
	if ($selection.val() == '4') {
		$('#form').bootstrapValidator('removeField', $('.datetime').find('[name="date_time_start"]'));
		$('#form').bootstrapValidator('removeField', $('.datetime').find('[name="date_time_end"]'));
		$('#form').bootstrapValidator('removeField', $('.time').find('[name="time_start"]'));
		$('#form').bootstrapValidator('removeField', $('.time').find('[name="time_end"]'));
		$('#form').bootstrapValidator('removeField', $('.backup').find('[name="staff_id"]'));
		$('#form').bootstrapValidator('removeField', $('.supportdoc').find('[name="document"]'));
		$('#form').bootstrapValidator('removeField', $('.suppdoc').find('[name="documentsupport"]'));

		$('#remove').remove();
		$('#wrapper').append(
			'<div id="remove">' +
				'<div class="form-group row mb-3 {{ $errors->has('leave_replacement_id') ? 'has-error' : '' }}">' +
					'{{ Form::label('nrla', 'Please Choose Your Replacement Leave : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-auto nrl">' +
						'<p>Total Non Replacement Leave = {{ $oi->sum('leave_balance') }} days</p>' +
						'<select name="leave_replacement_id" id="nrla" class="form-control">' +
							'<option value="">Please select</option>' +
@foreach( $oi as $po )
							'<option value="{{ $po->id }}" data-nrlbalance="{{ $po->leave_balance }}">On ' + moment( '{{ $po->working_date }}', 'YYYY-MM-DD' ).format('ddd Do MMM YYYY') + ', your leave balance = {{ $po->leave_balance }} day</option>' +
@endforeach
						'</select>' +
					'</div>' +
				'</div>' +

				'<div class="form-group row mb-3 {{ $errors->has('date_time_start') ? 'has-error' : '' }}">' +
					'{{ Form::label('from', 'From : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-auto datetime">' +
						'{{ Form::text('date_time_start', @$value, ['class' => 'form-control', 'id' => 'from', 'placeholder' => 'From : ', 'autocomplete' => 'off']) }}' +
					'</div>' +
				'</div>' +
				'<div class="form-group row mb-3 {{ $errors->has('date_time_end') ? 'has-error' : '' }}">' +
					'{{ Form::label('to', 'To : ', ['class' => 'col-sm-2 col-form-label']) }}' +
					'<div class="col-auto datetime">' +
						'{{ Form::text('date_time_end', @$value, ['class' => 'form-control', 'id' => 'to', 'placeholder' => 'To : ', 'autocomplete' => 'off']) }}' +
					'</div>' +
				'</div>' +

				'<div class="form-group row mb-3 {{ $errors->has('leave_type') ? 'has-error' : '' }}" id="wrapperday">' +
					'{{ Form::label('leave_type', 'Jenis Cuti : ', ['class' => 'col-sm-2 col-form-label removehalfleave']) }}' +
					'<div class="col-sm-10 removehalfleave" id="halfleave">' +
						'<div class="pretty p-default p-curve form-check-inline removehalfleave" id="removeleavehalf">' +
							'{{ Form::radio('leave_type', '1', true, ['id' => 'radio1', 'class' => ' removehalfleave']) }}' +
							'<div class="state p-success removehalfleave">' +
								'{{ Form::label('radio1', 'Cuti Penuh', ['class' => 'form-check-label removehalfleave']) }}' +
							'</div>' +
						'</div>' +
						'<div class="pretty p-default p-curve form-check-inline removehalfleave" id="appendleavehalf">' +
							'{{ Form::radio('leave_type', '2', NULL, ['id' => 'radio2', 'class' => ' removehalfleave']) }}' +
							'<div class="state p-success removehalfleave">' +
								'{{ Form::label('radio2', 'Cuti Separuh', ['class' => 'form-check-label removehalfleave']) }}' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="form-group row col-sm-10 offset-sm-2 {{ $errors->has('leave_half') ? 'has-error' : '' }} removehalfleave"  id="wrappertest">' +
					'</div>' +
				'</div>' +
			'</div>'
		);

		/////////////////////////////////////////////////////////////////////////////////////////
		// more option
		$('#form').bootstrapValidator('addField', $('.datetime').find('[name="date_time_start"]'));
		$('#form').bootstrapValidator('addField', $('.datetime').find('[name="date_time_end"]'));
		$('#form').bootstrapValidator('addField', $('.nrl').find('[name="leave_replacement_id"]'));

		/////////////////////////////////////////////////////////////////////////////////////////
		// enable select2
		$('#nrla').select2({ placeholder: 'Please select', 	width: '100%',
		});

		/////////////////////////////////////////////////////////////////////////////////////////
		// enable datetime for the 1st one
		$('#from').datetimepicker({
			icons: {
				time: "fas fas-regular fa-clock fa-beat",
				date: "fas fas-regular fa-calendar fa-beat",
				up: "fas fas-regular fa-arrow-up fa-beat",
				down: "fas fas-regular fa-arrow-down fa-beat",
				previous: 'fas fas-regular fa-arrow-left fa-beat',
				next: 'fas fas-regular fa-arrow-right fa-beat',
				today: 'fas fas-regular fa-calenday-day fa-beat',
				clear: 'fas fas-regular fa-broom-wide fa-beat',
				close: 'fas fas-regular fa-rectangle-xmark fa-beat'
			},
			format:'YYYY-MM-DD',
			useCurrent: false,
			minDate: moment().format('YYYY-MM-DD'),
			disabledDates: data,
			// daysOfWeekDisabled: [0],
			// minDate: data[1],
		})
		.on('dp.change dp.show dp.update', function(e) {
			$('#form').bootstrapValidator('revalidateField', 'date_time_start');
			var minDate = $('#from').val();
			$('#to').datetimepicker('minDate', minDate);
			if($('#from').val() === $('#to').val()) {
				if( $('.removehalfleave').length === 0) {
					$('#wrapperday').append(
						'{{ Form::label('leave_type', 'Jenis Cuti : ', ['class' => 'col-sm-2 col-form-label removehalfleave']) }}' +
						'<div class="col-sm-10 removehalfleave" id="halfleave">' +
							'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="removeleavehalf">' +
								'{{ Form::radio('leave_type', '1', true, ['id' => 'radio1', 'class' => ' removehalfleave']) }}' +
								'<div class="state p-success removehalfleave">' +
									'{{ Form::label('radio1', 'Cuti Penuh', ['class' => 'form-check-label removehalfleave']) }}' +
								'</div>' +
							'</div>' +
							'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="appendleavehalf">' +
								'{{ Form::radio('leave_type', '2', NULL, ['id' => 'radio2', 'class' => ' removehalfleave']) }}' +
								'<div class="state p-success removehalfleave">' +
									'{{ Form::label('radio2', 'Cuti Separuh', ['class' => 'form-check-label removehalfleave']) }}' +
								'</div>' +
							'</div>' +
						'</div>' +
						'<div class="form-group row col-sm-10 offset-sm-2 {{ $errors->has('leave_half') ? 'has-error' : '' }} removehalfleave"  id="wrappertest">' +
						'</div>'
					);
				}
			}
			if($('#from').val() !== $('#to').val()) {
				// $('.removehalfleave').remove();
				$('#to').val( $('#from').val() );
			}
		});
		
		$('#to').datetimepicker({
			icons: {
				time: "fas fas-regular fa-clock fa-beat",
				date: "fas fas-regular fa-calendar fa-beat",
				up: "fas fas-regular fa-arrow-up fa-beat",
				down: "fas fas-regular fa-arrow-down fa-beat",
				previous: 'fas fas-regular fa-arrow-left fa-beat',
				next: 'fas fas-regular fa-arrow-right fa-beat',
				today: 'fas fas-regular fa-calenday-day fa-beat',
				clear: 'fas fas-regular fa-broom-wide fa-beat',
				close: 'fas fas-regular fa-rectangle-xmark fa-beat'
			},
			format:'YYYY-MM-DD',
			useCurrent: false,
			minDate: moment().format('YYYY-MM-DD'),
			disabledDates:data,
			//daysOfWeekDisabled: [0],
		})
		.on('dp.change dp.show dp.update', function(e) {
			$('#form').bootstrapValidator('revalidateField', 'date_time_end');
			var maxDate = $('#to').val();
			$('#from').datetimepicker('maxDate', maxDate);
			if($('#from').val() === $('#to').val()) {
				if( $('.removehalfleave').length === 0) {
					$('#wrapperday').append(
						'{{ Form::label('leave_type', 'Jenis Cuti : ', ['class' => 'col-sm-2 col-form-label removehalfleave']) }}' +
						'<div class="col-sm-10 removehalfleave" id="halfleave">' +
							'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="removeleavehalf">' +
								'{{ Form::radio('leave_type', '1', true, ['id' => 'radio1', 'class' => ' removehalfleave']) }}' +
								'<div class="state p-success removehalfleave">' +
									'{{ Form::label('radio1', 'Cuti Penuh', ['class' => 'form-check-label removehalfleave']) }}' +
								'</div>' +
							'</div>' +
							'<div class="pretty p-default p-curve form-check form-check-inline removehalfleave" id="appendleavehalf">' +
								'{{ Form::radio('leave_type', '2', NULL, ['id' => 'radio2', 'class' => ' removehalfleave']) }}' +
								'<div class="state p-success removehalfleave">' +
									'{{ Form::label('radio2', 'Cuti Separuh Hari', ['class' => 'form-check-label removehalfleave']) }}' +
								'</div>' +
							'</div>' +
						'</div>' +
						'<div class="form-group row col-sm-10 offset-sm-2 {{ $errors->has('leave_half') ? 'has-error' : '' }} removehalfleave"  id="wrappertest">' +
						'</div>'
					);
				}
			}
			if($('#from').val() !== $('#to').val()) {
				// $('.removehalfleave').remove();
				$('#from').val( $('#to').val() );
			}
		});
		
		/////////////////////////////////////////////////////////////////////////////////////////
		// enable radio
		$(document).on('change', '#appendleavehalf :radio', function () {
			if (this.checked) {
				var daynow = moment($('#from').val(), 'YYYY-MM-DD').format('dddd');
				var datenow =$('#from').val();
		
				var data1 = $.ajax({
					url: "{{ route('leavedate.timeleave') }}",
					type: "POST",
					data: {date: datenow, _token: '{!! csrf_token() !!}', id: {{ \Auth::user()->belongstostaff->id }} },
					dataType: 'json',
					global: false,
					async:false,
					success: function (response) {
						// you will get response from your php page (what you echo or print)
						return response;
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				}).responseText;

				// convert data1 into json
				var obj = $.parseJSON( data1 );

				// checking so there is no double
				if( $('.removetest').length == 0 ) {
					$('#wrappertest').append(
						'<div class="pretty p-default p-curve form-check form-check-inline removetest">' +
							'<input type="radio" name="leave_half" value="' + obj.start_am + '/' + obj.end_am + '" id="am" checked="checked">' +
							'<div class="state p-primary">' +
								'<label for="am" class="form-check-label">' + moment(obj.start_am, 'HH:mm:ss').format('h:mm a') + ' to ' + moment(obj.end_am, 'HH:mm:ss').format('h:mm a') + '</label> ' +
							'</div>' +
						'</div>' +
						'<div class="pretty p-default p-curve form-check form-check-inline removetest">' +
							'<input type="radio" name="leave_half" value="' + obj.start_pm + '/' + obj.end_pm + '" id="pm">' +
							'<div class="state p-primary">' +
								'<label for="pm" class="form-check-label">' + moment(obj.start_pm, 'HH:mm:ss').format('h:mm a') + ' to ' + moment(obj.end_pm, 'HH:mm:ss').format('h:mm a') + '</label> ' +
							'</div>' +
						'</div>'
					);
				}
			}
		});
		
		//$(document).on('change', '#removeleavehalf :radio', function () {
		$('#removeleavehalf :radio').change(function() {
			if (this.checked) {

				// console.log( $('#nrla option:selected').data('nrlbalance') );
				if( $('#nrla option:selected').data('nrlbalance') == 0.5 ) {

					// especially for select 2, if no select2, remove change()
					$('#nrla option:selected').prop('selected', false).change();
					// $('#nrla').val('').change();
				}
				$('.removetest').remove();
			}
		});

		/////////////////////////////////////////////////////////////////////////////////////////
		// checking for half day click but select for 1 full day
		$('#nrla').change(function() {

			selectedOption = $('option:selected', this);

			$('#form').bootstrapValidator('revalidateField', 'leave_replacement_id');

			var nrlbal = selectedOption.data('nrlbalance');

			if (nrlbal == 0.5) {
				$('#radio2').prop('checked', true);
				// checking so there is no double

				var daynow = moment($('#from').val(), 'YYYY-MM-DD').format('dddd');
				var datenow =$('#from').val();
		
				var data1 = $.ajax({
					url: "{{ route('leavedate.timeleave') }}",
					type: "POST",
					data: {date: datenow, _token: '{!! csrf_token() !!}', id: {{ \Auth::user()->belongstostaff->id }} },
					dataType: 'json',
					global: false,
					async:false,
					success: function (response) {
						// you will get response from your php page (what you echo or print)
						return response;
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
					}
				}).responseText;

				// convert data1 into json
				var obj = $.parseJSON( data1 );

				// checking so there is no double
				if( $('.removetest').length == 0 ) {
					$('#wrappertest').append(
						'<div class="pretty p-default p-curve form-check form-check-inline removetest">' +
							'<input type="radio" name="leave_half" value="' + obj.start_am + '/' + obj.end_am + '" id="am" checked="checked">' +
							'<div class="state p-primary">' +
								'<label for="am" class="form-check-label">' + moment(obj.start_am, 'HH:mm:ss').format('h:mm a') + ' to ' + moment(obj.end_am, 'HH:mm:ss').format('h:mm a') + '</label> ' +
							'</div>' +
						'</div>' +
						'<div class="pretty p-default p-curve form-check form-check-inline removetest">' +
							'<input type="radio" name="leave_half" value="' + obj.start_pm + '/' + obj.end_pm + '" id="pm">' +
							'<div class="state p-primary">' +
								'<label for="pm" class="form-check-label">' + moment(obj.start_pm, 'HH:mm:ss').format('h:mm a') + ' to ' + moment(obj.end_pm, 'HH:mm:ss').format('h:mm a') + '</label> ' +
							'</div>' +
						'</div>'
					);
				}

			} else {
				if( nrlbal != 0.5 ) {
					$('#radio1').prop('checked', true);
					$('.removetest').remove();
				}
			}
		});
	}




});
@endsection

