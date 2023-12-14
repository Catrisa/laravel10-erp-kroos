<?php

namespace App\Http\Controllers\HumanResources\HRDept;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// for controller output
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

// load facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load laravel-Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffAppraisalExport;

// load models
use App\Models\Staff;
use App\Models\HumanResources\HRAttendance;
use App\Models\HumanResources\HRLeave;

// load helper
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

// load Carbon
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use \Carbon\CarbonInterval;

use Session;
use Throwable;
use Exception;
use Log;

class AppraisalExcelReportController extends Controller
{
	function __construct()
	{
		$this->middleware(['auth']);
		$this->middleware('highMgmtAccess:1|2|5,14|31', ['only' => ['index', 'show']]);
		$this->middleware('highMgmtAccessLevel1:1|5,14', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(): View
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(): View
	{
		return view('humanresources.hrdept.appraisal.report.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)/*: RedirectResponse*/
	{
		$validated = $request->validate(
			[
				'year' => 'required|integer',
			],
			[
				'year.required' => 'Please insert year',
			],
			[
				'year' => 'Apraisal Staff Year',
			]
		);

		$staffs = Staff::where('active', 1)->get();

		return Excel::download(new StaffAppraisalExport($request->year), 'Staff Appraisal '.$request->year.'.xlsx');
		// Session(['lastBatchId' => $batch->id]);
		// return response()->json(route('interview.index', ['id' => $batch->id]));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Staff $staff): View
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Staff $staff): View
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Staff $staff): RedirectResponse
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Staff $staff): RedirectResponse
	{
		//
	}
}
