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
// use App\Http\Requests\HumanResources\Attendance\AttendanceRequestUpdate;

// load facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load models
use App\Models\HumanResources\DepartmentPivot;
use App\Models\HumanResources\HRAppraisalSection;
use App\Models\HumanResources\HRAppraisalSectionSub;
use App\Models\HumanResources\HRAppraisalMainQuestion;
use App\Models\HumanResources\HRAppraisalQuestion;

// load paginator
use Illuminate\Pagination\Paginator;

// load cursor pagination
use Illuminate\Pagination\CursorPaginator;

// load array helper
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

// load Carbon
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use \Carbon\CarbonInterval;

use Session;

class AppraisalFormController extends Controller
{
  function __construct()
  {
    $this->middleware(['auth']);
    $this->middleware('highMgmtAccess:1|2|4|5,NULL', ['only' => ['index', 'show']]);
    $this->middleware('highMgmtAccessLevel1:1|5,14', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
  }

  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    // // ini_set('max_execution_time', 60000000000);
    // if ($request->date != NULL) {
    // 	$selected_date = $request->date;
    // } else {
    // 	$current_time = now();
    // 	$selected_date = $current_time->format('Y-m-d');
    // }

    // $attendance = HRAttendance::join('staffs', 'hr_attendances.staff_id', '=', 'staffs.id')
    // 	->select('hr_attendances.id as id', 'staff_id', 'daytype_id', 'attendance_type_id', 'attend_date', 'in', 'break', 'resume', 'out', 'time_work_hour', 'work_hour', 'leave_id', 'hr_attendances.remarks as remarks', 'hr_attendances.hr_remarks as hr_remarks', 'exception', 'hr_attendances.created_at as created_at', 'hr_attendances.updated_at as updated_at', 'hr_attendances.deleted_at as deleted_at', 'staffs.name as name', 'staffs.restday_group_id as restday_group_id', 'staffs.active as active')
    // 	->where('staffs.active', 1)
    // 	->where('attend_date', $selected_date)
    // 	// ->where(function(Builder $query) {
    // 	// 	$query->whereDate('attend_date', '>=', '2023-01-01')
    // 	// 	->whereDate('attend_date', '<=', '2023-12-31');
    // 	// })
    // 	->get();

    $departments = DepartmentPivot::all();

    return view('humanresources.hrdept.appraisal.form.index', ['departments' => $departments]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create($id): View
  {
    $department = DepartmentPivot::where('id', $id)->first();
    return view('humanresources.hrdept.appraisal.form.create', ['department' => $department]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {

    $p1_end = $request->p1_end;
    $p2_end = $request->p2_end;
    $p3_end = $request->p3_end;
    $p4_end = $request->p4_end;

    // P1
    for ($p1_start = 1; $p1_start <= $p1_end; $p1_start++) {

      if ($request->has('p1' . $p1_start)) {
        foreach ($request->{'p1' . $p1_start} as $key => $val) {
          if ($val['section'] != NULL) {
            HRAppraisalSection::create([
              'sort' => $val['section_sort'],
              'section' => $val['section'],
            ]);
          } else {
            HRAppraisalSection::create([
              'sort' => $val['section_sort'],
              'section' => preg_replace('/>\s*</', '><', $val['section_text']),
            ]);
          }

          $section_id = HRAppraisalSection::select('id')->orderBy('id', 'DESC')->first();


          // PIVOT DEPT APPRAISAL
          $section_id->belongstomanydepartmentpivot()->attach($request->department_id);


          // P2
          for ($p2_start = 1; $p2_start <= $p2_end; $p2_start++) {

            if ($request->has('p2' . $p1_start . $p2_start)) {
              foreach ($request->{'p2' . $p1_start . $p2_start} as $key => $val) {
                if ($val['sectionsub'] != NULL) {
                  HRAppraisalSectionSub::create([
                    'section_id' => $section_id->id,
                    'sort' => $val['sectionsub_sort'],
                    'section_sub' => $val['sectionsub'],
                  ]);
                } else {
                  HRAppraisalSectionSub::create([
                    'section_id' => $section_id->id,
                    'sort' => $val['sectionsub_sort'],
                    'section_sub' => preg_replace('/>\s*</', '><', $val['sectionsub_text']),
                  ]);
                }

                $sectionsub_id = HRAppraisalSectionSub::select('id')->orderBy('id', 'DESC')->first();


                // P3
                for ($p3_start = 1; $p3_start <= $p3_end; $p3_start++) {

                  if ($request->has('p3' . $p1_start . $p2_start . $p3_start)) {
                    foreach ($request->{'p3' . $p1_start . $p2_start . $p3_start} as $key => $val) {
                      if ($val['mainquestion'] != NULL) {
                        HRAppraisalMainQuestion::create([
                          'section_sub_id' => $sectionsub_id->id,
                          'sort' => $val['mainquestion_sort'],
                          'mark' => $val['mainquestion_mark'],
                          'main_question' => $val['mainquestion'],
                        ]);
                      } else {
                        HRAppraisalMainQuestion::create([
                          'section_sub_id' => $sectionsub_id->id,
                          'sort' => $val['mainquestion_sort'],
                          'mark' => $val['mainquestion_mark'],
                          'main_question' => preg_replace('/>\s*</', '><', $val['mainquestion_text']),
                        ]);
                      }

                      $mainquestion_id = HRAppraisalMainQuestion::select('id')->orderBy('id', 'DESC')->first();

                      
                      // P4
                      for ($p4_start = 1; $p4_start <= $p4_end; $p4_start++) {

                        if ($request->has('p4' . $p1_start . $p2_start . $p3_start . $p4_start)) {
                          foreach ($request->{'p4' . $p1_start . $p2_start . $p3_start . $p4_start} as $key => $val) {
                            if ($val['question'] != NULL) {
                              HRAppraisalQuestion::create([
                                'main_question_id' => $mainquestion_id->id,
                                'sort' => $val['question_sort'],
                                'mark' => $val['question_mark'],
                                'question' => $val['question'],
                              ]);
                            } else {
                              HRAppraisalQuestion::create([
                                'main_question_id' => $mainquestion_id->id,
                                'sort' => $val['question_sort'],
                                'mark' => $val['question_mark'],
                                'question' => preg_replace('/>\s*</', '><', $val['question_text']),
                              ]);
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    Session::flash('flash_message', 'Successfully Submit Appraisal Form.');
    return redirect()->route('appraisalform.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(): View
  {
    // return view('humanresources.hrdept.attendance.show', ['attendance' => $attendance]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(): View
  {
    // return view('humanresources.hrdept.attendance.edit', ['attendance' => $attendance]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(): RedirectResponse
  {
    // //dd($request->all());

    // $exception = (!request()->has('exception') == '1' ? '0' : '1');

    // $attendance->update([
    // 	'daytype_id' => $request->daytype_id,
    // 	'attendance_type_id' => $request->attendance_type_id,
    // 	'leave_id' => $request->leave_id,
    // 	'in' => $request->in,
    // 	'break' => $request->break,
    // 	'resume' => $request->resume,
    // 	'out' => $request->out,
    // 	'time_work_hour' => $request->time_work_hour,
    // 	'remarks' => ucwords(Str::of($request->remarks)->lower()),
    // 	'hr_remarks' => ucwords(Str::of($request->hr_remarks)->lower()),
    // 	'exception' => $exception,
    // ]);

    // $attendance->save();

    // Session::flash('flash_message', 'Data successfully updated!');
    // return redirect()->route('attendance.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(): RedirectResponse
  {
    //
  }
}
