<?php

namespace App\Http\Controllers\HumanResources\HRDept;

use App\Http\Controllers\Controller;

// models
use App\Models\HumanResources\HRLeaveReplacement;

// validation
use App\Http\Requests\HumanResources\ReplacementLeave\ReplacementRequestStore;
use App\Http\Requests\HumanResources\ReplacementLeave\ReplacementRequestUpdate;

// load paginator
use Illuminate\Pagination\Paginator;

// for controller output
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Session;
use Carbon\Carbon;

class ReplacementLeaveController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('highMgmtAccess:1|2|4|5,NULL', ['only' => ['index', 'show']]);
        $this->middleware('highMgmtAccess:1|5,14', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }





    public function replacement_ajax()
    {
        $data = [
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"],
            ["test"]
        ];

        return view('humanresources.hrdept.rleave.index', compact('data'));
    }









    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Paginator::useBootstrap();
        $replacements = HRLeaveReplacement::orderBy('id', 'desc')->paginate(30);
        return view('humanresources.hrdept.rleave.index', compact('replacements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('humanresources.hrdept.rleave.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReplacementRequestStore  $request): RedirectResponse
    {
        $staffids = $request->input('staff_id');

        $dateStart = Carbon::parse($request->date_start);
        $dateEnd = Carbon::parse($request->date_end);

        // Calculate the difference in days
        $diffInDays = $dateStart->diffInDays($dateEnd, true);
        $leave_total = $diffInDays + 1;

        // Loop through the checkbox values
        foreach ($staffids as $staffid) {
            HRLeaveReplacement::create([
                'staff_id' => $staffid,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'customer_id' => $request->customer_id,
                'reason' => $request->reason,
                'leave_total' => $leave_total,
                'leave_utilize' => '',
                'leave_balance' => $leave_total,
            ]);
        }

        Session::flash('flash_message', 'Successfully Add Replacement Leave.');
        return redirect()->route('rleave.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(HRLeaveReplacement $rleave): View
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HRLeaveReplacement $rleave): View
    {
        return view('humanresources.hrdept.rleave.edit', ['rleave' => $rleave]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReplacementRequestUpdate $request, HRLeaveReplacement $rleave): RedirectResponse
    {
        $rleave->update($request->only(['date_start', 'date_end', 'customer_id', 'reason', 'leave_total', 'leave_utilize', 'leave_balance']));

        Session::flash('flash_message', 'Data successfully updated!');
        return Redirect::route('rleave.index', $rleave);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, HRLeaveReplacement $rleave): JsonResponse
    {
        if ($request->table == 'replacement') {
            $HRLeaveReplacement = HRLeaveReplacement::destroy(
                [
                    'id' => $rleave['id']
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Your replacement leave has been deleted.',
            ]);
        }
    }
}
