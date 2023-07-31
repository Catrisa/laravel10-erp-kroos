<?php

namespace App\Http\Controllers\HumanResources\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

// load validation
use App\Http\Requests\HumanResources\Leave\HRLeaveRequestStore;

// load models
use App\Models\HRLeave;

class HRLeaveController extends Controller
{
	function __construct()
	{
		$this->middleware('auth');
		$this->middleware('leaveaccess', ['only' => ['show', 'edit', 'update']]);
	}
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return view('humanresources.leave.index');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('humanresources.leave.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(HRLeaveRequestStore $request)//: RedirectResponse
	{
		return $request->all();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(HRLeave $hrleave)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(HRLeave $hrleave)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(HRLeaveRequestStore $request, HRLeave $hrleave)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(HRLeave $hrleave)
	{
		//
	}
}
