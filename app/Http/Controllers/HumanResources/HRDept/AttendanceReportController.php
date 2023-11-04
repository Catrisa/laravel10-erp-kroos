<?php

namespace App\Http\Controllers\HumanResources\HRDept;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// for controller output
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// load validation
use App\Http\Requests\HumanResources\Attendance\AttendanceRequestUpdate;

// load facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load models
use App\Models\HumanResources\HRAttendance;
use App\Models\Staff;

// load paginator
use Illuminate\Pagination\Paginator;

// load cursor pagination
use Illuminate\Pagination\CursorPaginator;

// load array helper
use Illuminate\Support\Arr;

// load Carbon
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use \Carbon\CarbonInterval;

use Session;

class AttendanceReportController extends Controller
{
	function __construct()
	{
		$this->middleware(['auth']);
		$this->middleware('highMgmtAccess:1|2|5,NULL', ['only' => ['index', 'show']]);
		$this->middleware('highMgmtAccess:1|5,14', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
	}

	public function index(): View
	{
		return view('humanresources.hrdept.attendance.attendancereport.index');
	}

	public function create(Request $request): View
	{
		// dd($request->all());
		$sa = HRAttendance::select('staff_id')
					->whereIn('staff_id', $request->staff_id)
					->where(function (Builder $query) use ($request){
						$query->whereDate('attend_date', '>=', $request->from)
						->whereDate('attend_date', '<=', $request->to);
					})
					->groupBy('hr_attendances.staff_id')
					->get();

		return view('humanresources.hrdept.attendance.attendancereport.create', ['sa' => $sa, 'request' => $request]);
	}

}
