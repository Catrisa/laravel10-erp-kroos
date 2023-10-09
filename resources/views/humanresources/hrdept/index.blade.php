@extends('layouts.app')

@section('content')
<div class="col-sm-12 row">
@include('humanresources.hrdept.navhr')
	<div class="row justify-content-center">
		<div class="col-sm-12">
			<canvas id="myChart" width="200" height="75"></canvas>
		</div>
	</div>
</div>
@endsection

@section('js')
/////////////////////////////////////////////////////////////////////////////////////////
@endsection

@section('nonjquery')
/////////////////////////////////////////////////////////////////////////////////////////
// chartjs also dont use jquery

// const data = [
// 					{ month: 'January', percentage: 90.59, workdays: 31, leaves: 1, absents: 1, working_days: 25 },
// 					{ month: 'February', percentage: 93.23, workdays: 28, leaves: 1, absents: 1, working_days: 25 },
// 					{ month: 'March', percentage: 91.5, workdays: 31, leaves: 1, absents: 1, working_days: 25 },
// 					{ month: 'April', percentage: 93.45, workdays: 30, leaves: 1, absents: 1, working_days: 25 },
// 					{ month: 'May', percentage: 81.23, workdays: 31, leaves: 1, absents: 1, working_days: 25 },
// 					{ month: 'June', percentage: 79.23, workdays: 30, leaves: 1, absents: 1, working_days: 25 },
// 					{ month: 'July', percentage: 95.59, workdays: 31, leaves: 1, absents: 1, working_days: 25 },
// 			];

var xmlhttp = new XMLHttpRequest();
// xmlhttp.open(method, URL, [async, user, password])
xmlhttp.open("GET", '{!! route('staffdaily', ['_token' => csrf_token()]) !!}', true);
// xmlhttp.responseType = 'json';
// xmlhttp.onreadystatechange = myfunction;
xmlhttp.send();
xmlhttp.onload = function() {
// alert(`Loaded: ${data.status} ${data.response}`);
// return data.status;
	const data = JSON.parse(xmlhttp.responseText);
//	console.log(data);

	new Chart(document.getElementById('myChart'), {
		type: 'line',
		data: {
			labels: data.map(row => row.month),
			datasets: [
						{
							label: 'Attendance Percentage By Month(%)',
							data: data.map(row => row.percentage)
						},
						{
							label: 'Leaves By Month',
							data: data.map(row => row.leaves)
						},
						{
							label: 'Absents By Month',
							data: data.map(row => row.absents)
						},
						{
							label: 'Working Days By Month (Person Available)',
							data: data.map(row => row.working_days)
						},
						{
							label: 'Work Days By Month',
							data: data.map(row => row.workdays)
						},
			]
		},
		options: {
			responsive: true,
			scales: {
				y: {
					beginAtZero: true
				}
			},
			interaction: {
				intersect: false,
				mode: 'index',
			},
		},
		plugins: {
			legend: {
				position: 'top',
			},
			title: {
				display: true,
				text: 'Attendance Statistic Daily'
			},
		},
	});
};

/////////////////////////////////////////////////////////////////////////////////////////
@endsection


