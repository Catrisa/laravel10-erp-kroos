@extends('layouts.app')

@section('content')
<style>
  /* div {
  border: 1px solid black;
}

  table,
  thead,
  tr,
  th,
  td {
    border: 1px solid black;
  } */
</style>

<div class="container">
  @include('humanresources.hrdept.navhr')
  <h4>Staff Discipline</h4>
  <div>
    <table id="replacement" class="table table-hover table-sm align-middle" style="font-size:12px">
      <thead>
        <tr>
          <th class="text-center" style="max-width: 30px;">ID</th>
          <th style="max-width: 120px;">Name</th>
          <th class="text-center" style="max-width: 55px;">Date</th>
          <th class="text-center" style="max-width: 80px;">Department</th>
          <th style="max-width: 110px;">Disciplinary Action</th>
          <th style="max-width: 200px;">Violation</th>
          <th>Reason</th>
          <th class="text-center" style="max-width: 55px;">Softcopy</th>
          <th class="text-center" style="max-width: 40px;">Edit</th>
          <th class="text-center" style="max-width: 40px;">Cancel</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($disciplinary as $discipline)

        <tr>
          <td class="text-center">
            {{ $discipline->belongstostaff->hasmanylogin()->where('logins.active', 1)->first()->username }}
          </td>
          <td class="text-truncate" style="max-width: 120px;" data-toggle="tooltip" title="{{ $discipline->belongstostaff->name }}">
            {{ $discipline->belongstostaff->name }}
          </td>
          <td class="text-center">
            {{ $discipline->date }}
          </td>
          <td class="text-center">
            {{ $discipline->belongstostaff->belongstomanydepartment()?->wherePivot('main', 1)->first()->code }}
          </td>
          <td class="text-truncate" style="max-width: 110px;" data-toggle="tooltip" title="{{ $discipline->belongstooptdisciplinaryaction->disciplinary_action }}">
            {{ $discipline->belongstooptdisciplinaryaction->disciplinary_action }}
          </td>
          <td class="text-truncate" style="max-width: 200px;" data-toggle="tooltip" title="{{ $discipline->belongstooptviolation->violation }}">
            {{ $discipline->belongstooptviolation->violation }}
          </td>
          <td class="text-truncate" style="max-width: 1px;" data-toggle="tooltip" title="{{ $discipline->reason }}">
            {{ $discipline->reason }}
          </td>
          <td class="text-center">
            @if ($discipline->softcopy)
            <a href="#">
              <i class="bi bi-file-text" style="font-size: 15px;"></i>
            </a>
            @endif
          </td>
          <td class="text-center">
            <a href="{{ route('discipline.edit', $discipline->id) }}">
              <i class="bi bi-pencil-square" style="font-size: 15px;"></i>
            </a>
          </td>
          <td class="text-center">
            <a href="#">
              <i class="bi bi-x-square delete_discipline" data-id="{{ $discipline->id }}" data-table="discipline" style="font-size: 15px;"></i>
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="d-flex justify-content-center">
      {{ $disciplinary->links() }}
    </div>
  </div>
</div>
@endsection


@section('js')
/////////////////////////////////////////////////////////////////////////////////////////
// datatables
$.fn.dataTable.moment( 'D MMM YYYY' );
$.fn.dataTable.moment( 'h:mm a' );
$('#replacement').DataTable({
"paging": false,
"lengthMenu": [ [-1], ["All"] ],
"columnDefs": [
{ type: 'date', 'targets': [2] },
{ type: 'time', 'targets': [3] },
],
"order": [ 2, 'desc' ], // sorting the column descending
responsive: true
})


$(function () {
$('[data-toggle="tooltip"]').tooltip()
})


/////////////////////////////////////////////////////////////////////////////////////////
// DELETE
$(document).on('click', '.delete_discipline', function(e){
var ackID = $(this).data('id');
var ackTable = $(this).data('table');
SwalDelete(ackID, ackTable);
e.preventDefault();
});

function SwalDelete(ackID, ackTable){
swal.fire({
title: 'Delete Discipline',
text: 'Are you sure to delete this discipline?',
icon: 'info',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
cancelButtonText: 'Cancel',
confirmButtonText: 'Yes',
showLoaderOnConfirm: true,

preConfirm: function() {
return new Promise(function(resolve) {
$.ajax({
url: '{{ url('discipline') }}' + '/' + ackID,
type: 'DELETE',
dataType: 'json',
data: {
id: ackID,
table: ackTable,
_token : $('meta[name=csrf-token]').attr('content')
},
})
.done(function(response){
swal.fire('Accept', response.message, response.status)
.then(function(){
window.location.reload(true);
});
})
.fail(function(){
swal.fire('Oops...', 'Something went wrong with ajax!', 'error');
})
});
},
allowOutsideClick: false
})
.then((result) => {
if (result.dismiss === swal.DismissReason.cancel) {
swal.fire('Cancel Action', '', 'info')
}
});
}
//auto refresh right after clicking OK button
$(document).on('click', '.swal2-confirm', function(e){
window.location.reload(true);
});
@endsection


@section('nonjquery')

@endsection