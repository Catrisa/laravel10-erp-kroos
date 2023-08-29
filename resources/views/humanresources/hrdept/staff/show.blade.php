@extends('layouts.app')

@section('content')
<div class="col-sm-12 row justify-content-center align-items-start">
@include('humanresources.hrdept.navhr')
	<h4 class="align-items-center">Profile {{ $staff->name }} <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-person-lines-fill"></i> Edit</a></h4>
	<div class="d-flex flex-column align-items-center text-center p-3 py-5">
		<img class="rounded-5 mt-3" width="180px" src="{{ asset('storage/user_profile/' . $staff->image) }}">
		<span class="font-weight-bold">{{ $staff->name }}</span>
		<span class="font-weight-bold">{{ $staff->hasmanylogin()->where('active', 1)->first()->username }}</span>
		<span> </span>
	</div>
	<div class="row justify-content-center">
		<div class="col-sm-6 row gy-1 gx-1 align-items-start">
			<div class="col-5">Name :</div>
			<div class="col-7">{{ $staff->name }}</div>
			<div class="col-5">Identity Card/Passport :</div>
			<div class="col-7">{{ $staff->ic }}</div>
			<div class="col-5">Religion :</div>
			<div class="col-7">{{ $staff->belongstoreligion?->religion }}</div>
			<div class="col-5">Gender :</div>
			<div class="col-7">{{ $staff->belongstogender?->gender }}</div>
			<div class="col-5">Race :</div>
			<div class="col-7">{{ $staff->belongstorace?->race }}</div>
			<div class="col-5">Nationality :</div>
			<div class="col-7">{{ $staff->belongstonationality?->country }}</div>
			<div class="col-5">Marital Status :</div>
			<div class="col-7">{{ $staff->belongstomaritalstatus?->marital_status }}</div>
			<div class="col-5">Email :</div>
			<div class="col-7">{{ $staff->email }}</div>
			<div class="col-5">Address :</div>
			<div class="col-7">{{ $staff->address }}</div>
			<div class="col-5">Place of Birth :</div>
			<div class="col-7">{{ $staff->place_of_birth }}</div>
			<div class="col-5">Mobile :</div>
			<div class="col-7">{{ $staff->mobile }}</div>
			<div class="col-5">Phone :</div>
			<div class="col-7">{{ $staff->phone }}</div>
			<div class="col-5">Date of Birth :</div>
			<div class="col-7">{{ \Carbon\Carbon::parse($staff->dob)->format('j M Y') }}</div>
			<div class="col-5">CIMB Account :</div>
			<div class="col-7">{{ $staff->cimb_account }}</div>
			<div class="col-5">EPF Account :</div>
			<div class="col-7">{{ $staff->epf_account }}</div>
			<div class="col-5">Income Tax No :</div>
			<div class="col-7">{{ $staff->income_tax_no }}</div>
			<div class="col-5">SOCSO No :</div>
			<div class="col-7">{{ $staff->socso_no }}</div>
			<div class="col-5">Weight :</div>
			<div class="col-7">{{ $staff->weight }} kg</div>
			<div class="col-5">Height :</div>
			<div class="col-7">{{ $staff->height }} cm</div>
			<div class="col-5">Date Join :</div>
			<div class="col-7">{{ \Carbon\Carbon::parse($staff->join)->format('j M Y') }}</div>
			<div class="col-5">Date Confirmed :</div>
			<div class="col-7">{{ \Carbon\Carbon::parse($staff->confirmed)->format('j M Y') }}</div>
			<div class="col-5">Spouse :</div>
			<div class="col-7">
				@if($staff->hasmanyspouse()?->get()->count())
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>Name</th>
							<th>Phone</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanyspouse()?->get() as $sp)
						<tr>
							<td>$sp->spouse</td>
							<td>$sp->phone</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				@endif
			</div>
			<div class="col-5">Children :</div>
			<div class="col-7">
				@if($staff->hasmanychildren()?->get()->count())
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>Name</th>
							<th>Age</th>
							<th>Tax Exemption (%)</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanychildren()?->get() as $sc)
						<tr>
							<td>{{$sc->children}}</td>
							<td>{{ \Carbon\Carbon::parse($sc->dob)->toPeriod(now(), 1, 'year')->count() }} year/s</td>
							<td>{{ $sc->belongstotaxexemptionpercentage?->tax_exemption_percentage }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				@endif
			</div>
			<div class="col-5">Emergency Contact :</div>
			<div class="col-7">
				@if($staff->hasmanyemergency()?->get()->count())
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>Name</th>
							<th>Phone</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanyemergency()?->get() as $sc)
						<tr>
							<td>{{ $sc->contact_person }}</td>
							<td>{{ $sc->phone }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				@endif
			</div>
		</div>
		<div class="col-sm-6 row gy-1 gx-1 align-items-start">
			<div class="col-5">System Administrator :</div>
			<div class="col-7">{{ $staff->belongstoauthorised?->authorise }}</div>
			<div class="col-5">Staff Status :</div>
			<div class="col-7">{{ $staff->belongstostatus?->status }}</div>
			<div class="col-5">Category :</div>
			<div class="col-7">{{ $staff->belongstomanydepartment()?->wherePivot('main', 1)->first()->belongstocategory?->category }}</div>
			<div class="col-5">Branch :</div>
			<div class="col-7">{{ $staff->belongstomanydepartment()?->wherePivot('main', 1)->first()->belongstobranch?->location }}</div>
			<div class="col-5">Department :</div>
			<div class="col-7">{{ $staff->belongstomanydepartment()?->wherePivot('main', 1)->first()->department }}</div>
			<div class="col-5">Leave Approval Flow :</div>
			<div class="col-7">{{ $staff->belongstoleaveapprovalflow?->description }}</div>
			<div class="col-5">RestDay Group :</div>
			<div class="col-7">{{ $staff->belongstorestdaygroup?->group }}</div>
			<div class="col-5">Cross Backup To :</div>
			<?php
			$cb = $staff->crossbackupto()->get();
			?>
			<div class="col-7">
				@if($cb->count())
				<ul>
					@foreach($cb as $r)
					<li>{{ $r->name }}</li>
					@endforeach
				</ul>
				@endif
			</div>
			<div class="col-5">Cross Backup For :</div>
			<?php
			$cbf = $staff->crossbackupfrom()->get();
			?>
			<div class="col-7">
				@if($cbf->count())
				<ul>
					@foreach($cbf as $rf)
					<li>{{ $rf->name }}</li>
					@endforeach
				</ul>
				@endif
			</div>
			@if($staff->hasmanyleaveannual()?->get()->count())
			<div class="col-5">Annual Leave :</div>
			<div class="col-7">
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>Year</th>
							<th>Annual Leave</th>
							<th>Annual Leave Adjustment</th>
							<th>Annual Leave Utilize</th>
							<th>Annual Leave Balance</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanyleaveannual()->orderBy('year', 'DESC')->get() as $al)
						<tr>
							<td>{{ $al->year }}</td>
							<td>{{ $al->annual_leave }}</td>
							<td>{{ $al->annual_leave_adjustment }}</td>
							<td>{{ $al->annual_leave_utilize }}</td>
							<td>{{ $al->annual_leave_balance }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@endif
			@if($staff->hasmanyleavemc()?->get()->count())
			<div class="col-5">MC Leave :</div>
			<div class="col-7">
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>Year</th>
							<th>MC Leave</th>
							<th>MC Leave Adjustment</th>
							<th>MC Leave Utilize</th>
							<th>MC Leave Balance</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanyleavemc()->orderBy('year', 'DESC')->get() as $al)
						<tr>
							<td>{{ $al->year }}</td>
							<td>{{ $al->mc_leave }}</td>
							<td>{{ $al->mc_leave_adjustment }}</td>
							<td>{{ $al->mc_leave_utilize }}</td>
							<td>{{ $al->mc_leave_balance }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@endif
			@if($staff->gender_id == 2)
			@if($staff->hasmanyleavematernity()?->get()->count())
			<div class="col-5">Maternity Leave :</div>
			<div class="col-7">
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>Year</th>
							<th>Maternity Leave</th>
							<th>Maternity Leave Adjustment</th>
							<th>Maternity Leave Utilize</th>
							<th>Maternity Leave Balance</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanyleavematernity()->orderBy('year', 'DESC')->get() as $al)
						<tr>
							<td>{{ $al->year }}</td>
							<td>{{ $al->maternity_leave }}</td>
							<td>{{ $al->maternity_leave_adjustment }}</td>
							<td>{{ $al->maternity_leave_utilize }}</td>
							<td>{{ $al->maternity_leave_balance }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@endif
			@endif
			@if($staff->hasmanyleavereplacement()?->get()->count())
			<div class="col-5">Replacement Leave :</div>
			<div class="col-7">
				<table class="table table-sm table-hover" style="font-size:12px;">
					<thead>
						<tr>
							<th>From</th>
							<th>To</th>
							<th>Location</th>
							<th>Reason</th>
							<th>Total Day/s</th>
							<th>Leave Utilize</th>
							<th>Leave Balance</th>
						</tr>
					</thead>
					<tbody>
					@foreach($staff->hasmanyleavereplacement()->orderBy('date_start', 'DESC')->get() as $al)
						<tr>
							<td>{{ \Carbon\Carbon::parse($al->date_start)->format('j M Y') }}</td>
							<td>{{ \Carbon\Carbon::parse($al->date_end)->format('j M Y') }}</td>
							<td>{{ $al->location }}</td>
							<td>{{ $al->reason }}</td>
							<td>{{ $al->maternity_leave_balance }}</td>
							<td>{{ $al->leave_total }}</td>
							<td>{{ $al->leave_utilize }}</td>
							<td>{{ $al->leave_balance }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			@endif
		</div>
	</div>
	<div class="row justify-content-center">
		<p></p>
		<div class="col-sm-12 row gy-1 gx-1 align-items-start">
			<h4 class="align-items-center">Leave</h4>
			@if(\App\Models\HumanResources\HRLeave::where('staff_id', $staff->id)->get()->count())
			<table id="leave" class="table table-sm table-hover" style="font-size:12px;">
				<thead>
					<tr>
						<th>No</th>
						<th>Type</th>
						<th>From</th>
						<th>To</th>
						<th>Duration</th>
						<th>Reason</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@foreach(\App\Models\HumanResources\HRLeave::where('staff_id', $staff->id)->orderBy('date_time_start', 'DESC')->orderBy('leave_type_id', 'ASC')->orderBy('leave_status_id', 'DESC')->get() as $ls)
<?php
$dts = \Carbon\Carbon::parse($ls->date_time_start)->format('Y');
$dte = \Carbon\Carbon::parse($ls->date_time_end)->format('j M Y g:i a');
$arr = str_split( $dts, 2 );
// only available if only now is before date_time_start and active is 1
$dtsl = \Carbon\Carbon::parse( $ls->date_time_start );
$dt = \Carbon\Carbon::now()->lte( $dtsl );

if ( ($ls->leave_type_id == 9) || ($ls->leave_type_id != 9 && $ls->half_type_id == 2) || ($ls->leave_type_id != 9 && $ls->half_type_id == 1) ) {
	$dts = \Carbon\Carbon::parse($ls->date_time_start)->format('j M Y g:i a');
	$dte = \Carbon\Carbon::parse($ls->date_time_end)->format('j M Y g:i a');

	if ($ls->leave_type_id != 9) {
		if ($ls->half_type_id == 2) {
			$dper = $ls->period_day.' Day';
		} elseif($ls->half_type_id == 1) {
			$dper = $ls->period_day.' Day';
		}
	}elseif ($ls->leave_type_id == 9) {
		$i = \Carbon\Carbon::parse($ls->period_time);
		$dper = $i->hour.' hour, '.$i->minute.' minutes';
	}

} else {
	$dts = \Carbon\Carbon::parse($ls->date_time_start)->format('j M Y ');
	$dte = \Carbon\Carbon::parse($ls->date_time_end)->format('j M Y ');
	$dper = $ls->period_day.' day/s';
}
?>
					<tr>
						<td>HR9-{{ str_pad( $ls->leave_no, 5, "0", STR_PAD_LEFT ) }}/{{ $arr[1] }}</td>
						<td>{{ $ls->belongstooptleavetype->leave_type_code }}</td>
						<td>{{ $dts }}</td>
						<td>{{ $dte }}</td>
						<td>{{ $dper }}</td>
						<td data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-html="true" data-bs-title="{{ $ls->reason }}">{{ Str::of($ls->reason)->words(3, ' >') }}</td>
						<td>
							@if(is_null($ls->leave_status_id))
								Pending
							@else
								{{ $ls->belongstooptleavestatus->status }}
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
		</div>
	</div>
</div>
@endsection

@section('js')
/////////////////////////////////////////////////////////////////////////////////////////
// tooltip on reason
$(document).ready(function(){
	$('[data-bs-toggle="tooltip"]').tooltip();
});

/////////////////////////////////////////////////////////////////////////////////////////
// datatables
$.fn.dataTable.moment( 'D MMM YYYY' );
$.fn.dataTable.moment( 'D MMM YYYY h:mm a' );
$('#leave').DataTable({
	"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
	"order": [[2, "asc" ]],	// sorting the 6th column descending
	responsive: true
});

@endsection
