@extends ('layouts.app')

@section('content')

<?php
$gender = App\Models\HumanResources\OptGender::all()->pluck('gender', 'id')->sortKeys()->toArray();
$nationality = App\Models\HumanResources\OptCountry::all()->pluck('country', 'id')->sortKeys()->toArray();
$religion = App\Models\HumanResources\OptReligion::all()->pluck('religion', 'id')->sortKeys()->toArray();
$race = App\Models\HumanResources\OptRace::all()->pluck('race', 'id')->sortKeys()->toArray();
$marital_status = App\Models\HumanResources\OptMaritalStatus::all()->pluck('marital_status', 'id')->sortKeys()->toArray();
$relationship = App\Models\HumanResources\OptRelationship::all()->pluck('relationship', 'id')->sortKeys()->toArray();
$emergencies = $profile->hasmanyemergency()->get();
?>

<div class="container rounded bg-white mt-2 mb-2">

  {!! Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PATCH', 'id' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'files' => true]) !!}

  <div class="row">
    <div class="col-md-3 border-right">
      <div class="d-flex flex-column align-items-center text-center p-3 py-5">
        <img class="rounded-5 mt-3" width="180px" src="{{ asset('storage/user_profile/' . $profile->image) }}">
        <span class="font-weight-bold">{{ $profile-> name}}</span>
        <span class="font-weight-bold">{{ $profile-> hasmanylogin() -> where('active', 1) -> first() -> username}}</span>
        <span> </span>
      </div>
    </div>
    <div class="col-md-5 border-right">
      <div class="p-3 py-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="text-right">Profile Update</h4>
        </div>

        <div class="row mt-3">
          <div class="col-md-12">
            <label class="labels">Name</label>
            <input type="text" class="form-control" placeholder="enter name" value="{{ $profile->name }}" readonly>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6 {{ $errors->has('ic') ? 'has-error' : '' }}">
            <label class="labels">IC</label>
            {!! Form::text( 'ic', @$value, ['class' => 'form-control', 'id' => 'ic', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
          <div class="col-md-6 {{ $errors->has('mobile') ? 'has-error' : '' }}">
            <label class="labels">PHONE NUMBER</label>
            {!! Form::text( 'mobile', @$value, ['class' => 'form-control', 'id' => 'mobile', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-12 {{ $errors->has('email') ? 'has-error' : '' }}">
            <label class="labels">EMAIL</label>
            {!! Form::text( 'email', @$value, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-12 {{ $errors->has('address') ? 'has-error' : '' }}">
            <label class="labels">ADDRESS</label>
            {!! Form::text( 'address', @$value, ['class' => 'form-control', 'id' => 'address', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-12">
            <label class="labels">DEPARTMENT</label>
            <input type="text" class="form-control" placeholder="enter name" value="{{ $profile->belongstomanydepartment()->first()->department }}" readonly>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6">
            <label class="labels">CATEGORY</label>
            <input type="text" class="form-control" placeholder="enter name" value="{{ $profile->belongstomanydepartment->first()->belongstocategory->category }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="labels">SATURDAY GROUPING</label>
            <input type="text" class="form-control" placeholder="enter name" value="Group {{ $profile->restday_group_id }}" readonly>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6 {{ $errors->has('dob') ? 'has-error' : '' }}">
            <label class="labels">DATE OF BIRTH</label>
            {!! Form::text( 'dob', @$value, ['class' => 'form-control', 'id' => 'dob', 'autocomplete' => 'off'] ) !!}
          </div>
          <div class="col-md-6 {{ $errors->has('gender_id') ? 'has-error' : '' }}">
            <label class="labels">GENDER</label>
            {!! Form::select( 'gender_id', $gender, @$value, ['class' => 'form-control', 'id' => 'gender_id', 'placeholder' => 'Please Select', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6 {{ $errors->has('nationality_id') ? 'has-error' : '' }}">
            <label class="labels">NATIONALITY</label>
            {!! Form::select( 'nationality_id', $nationality, @$value, ['class' => 'form-control', 'id' => 'nationality_id', 'placeholder' => 'Please Select', 'autocomplete' => 'off'] ) !!}
          </div>
          <div class="col-md-6 {{ $errors->has('race_id') ? 'has-error' : '' }}">
            <label class="labels">RACE</label>
            {!! Form::select( 'race_id', $race, @$value, ['class' => 'form-control', 'id' => 'race_id', 'placeholder' => 'Please Select', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6 {{ $errors->has('religion_id') ? 'has-error' : '' }}">
            <label class="labels">RELIGION</label>
            {!! Form::select( 'religion_id', $religion, @$value, ['class' => 'form-control', 'id' => 'religion_id', 'placeholder' => 'Please Select', 'autocomplete' => 'off'] ) !!}
          </div>
          <div class="col-md-6 {{ $errors->has('marital_status_id') ? 'has-error' : '' }}">
            <label class="labels">MARITAL STATUS</label>
            {!! Form::select( 'marital_status_id', $marital_status, @$value, ['class' => 'form-control', 'id' => 'marital_status_id', 'placeholder' => 'Please Select', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6">
            <label class="labels">JOIN DATE</label>
            <input type="text" class="form-control" placeholder="enter name" value="{{ \Carbon\Carbon::parse($profile->join)->format('d F Y') }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="labels">CONFIRM DATE</label>
            <input type="text" class="form-control" placeholder="enter name" value="{{ \Carbon\Carbon::parse($profile->confirmed)->format('d F Y') }}" readonly>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 border-right">
      <div class="p-3 py-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="text-right">Emergency Contact</h4>
          <span class="border px-3 p-1 add-experience btn btn-sm btn-outline-secondary">
            <i class="fa fa-plus"></i>&nbsp;Contact
          </span>
        </div>

        <?php $i = 1; ?>
        @if ($emergencies->isNotEmpty())
        @foreach ($emergencies as $emergency)

        <input type="hidden" name="emer[{{ $i }}][id]" value="{{ $emergency->id }}">

        <div class="row mt-3">
          <div class="col-md-12 {{ $errors->has('emer.'.$i.'.contact_person') ? 'has-error' : '' }}">
            <label class="labels">NAME</label>
            {!! Form::text( "emer[$i][contact_person]", @$emergency->contact_person, ['class' => 'form-control', 'id' => 'contact_person', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6 {{ $errors->has('emer[$i][relationship_id]') ? 'has-error' : '' }}">
            <label class="labels">RELATIONSHIP</label>
            {!! Form::select( "emer[$i][relationship_id]", $relationship, @$emergency->relationship_id, ['class' => 'form-control', 'id' => 'relationship_id', 'placeholder' => 'Please Select', 'autocomplete' => 'off'] ) !!}
          </div>
          <div class="col-md-6 {{ $errors->has('emer[$i][phone]') ? 'has-error' : '' }}">
            <label class="labels">PHONE NUMBER</label>
            {!! Form::text( "emer[$i][phone]", @$emergency->phone, ['class' => 'form-control', 'id' => 'phone', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-12 {{ $errors->has('emer[$i][address]') ? 'has-error' : '' }}">
            <label class="labels">ADDRESS</label>
            {!! Form::text( "emer[$i][address]", @$emergency->address, ['class' => 'form-control', 'id' => 'emergency_address', 'placeholder' => 'Please Insert', 'autocomplete' => 'off'] ) !!}
          </div>
        </div>

        <div class="row mt-4"></div>
        <?php $i++; ?>
        @endforeach
        @endif

      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-9 container">
      <div class="text-center">
        {!! Form::button('Save', ['class' => 'btn btn-sm btn-outline-secondary', 'type' => 'submit']) !!}
      </div>
    </div>
  </div>

  {!! Form::close() !!}

  <div class="row mt-3">
    <div class="col-md-3"></div>
    <div class="col-md-9">
      <div class="text-center">
        <a href="{{ url()->previous() }}">
          <button class="btn btn-sm btn-outline-secondary">Back</button>
        </a>
      </div>
    </div>
  </div>

</div>

@endsection

@section('js')
  /////////////////////////////////////////////////////////////////////////////////////////
  $('#dob').datetimepicker({
    icons: {
      time: "fas fas-regular fa-clock fa-beat",
      date: "fas fas-regular fa-calendar fa-beat",
      up: "fa-regular fa-circle-up fa-beat",
      down: "fa-regular fa-circle-down fa-beat",
      previous: 'fas fas-regular fa-arrow-left fa-beat',
      next: 'fas fas-regular fa-arrow-right fa-beat',
      today: 'fas fas-regular fa-calenday-day fa-beat',
      clear: 'fas fas-regular fa-broom-wide fa-beat',
      close: 'fas fas-regular fa-rectangle-xmark fa-beat'
    },
    format: 'YYYY-MM-DD',
    useCurrent: false,
  });

  /////////////////////////////////////////////////////////////////////////////////////////
  $('#nationality_id').select2({
    placeholder: 'Please Select',
    width: '100%',
    allowClear: true,
    closeOnSelect: true,
  });

  /////////////////////////////////////////////////////////////////////////////////////////
  // VALIDATOR
  $(document).ready(function() {
    $('#form').bootstrapValidator({
      feedbackIcons: {
        valid: '',
        invalid: '',
        validating: ''
      },
      fields: {
        ic: {
          validators: {
            notEmpty: {
              message: 'Please insert ic.'
            },
            numeric: {
              message: 'The value is not an numeric'
            }
          }
        },

        mobile: {
          validators: {
            notEmpty: {
              message: 'Please insert mobile number.'
            },
            numeric: {
              message: 'The value is not an numeric'
            }
          }
        },

        email: {
          validators: {
            notEmpty: {
              message: 'Please insert email.'
            },
            emailAddress: {
              message: 'The value is not a valid email.'
            }
          }
        },

        address: {
          validators: {
            notEmpty: {
              message: 'Please insert address.'
            }
          }
        },

        dob: {
          validators: {
            notEmpty: {
              message: 'Please insert date of birth.'
            }
          }
        },

        gender_id: {
          validators: {
            notEmpty: {
              message: 'Please select a gender.'
            }
          }
        },

        nationality_id: {
          validators: {
            notEmpty: {
              message: 'Please select a nationality.'
            }
          }
        },

        race_id: {
          validators: {
            notEmpty: {
              message: 'Please select a race.'
            }
          }
        },

        religion_id: {
          validators: {
            notEmpty: {
              message: 'Please select a religion.'
            }
          }
        },

        marital_status_id: {
          validators: {
            notEmpty: {
              message: 'Please select a marital status.'
            }
          }
        },

        <?php $i = 1; ?>
        <?php foreach ($emergencies as $emergency) { ?> 
          'emer[{{ $i }}][contact_person]': {
            validators: {
              notEmpty: {
                message: 'Please insert contact person.'
              }
            }
          },

          'emer[{{ $i }}][relationship_id]': {
            validators: {
              notEmpty: {
                message: 'Please select a relationship.'
              }
            }
          },

          'emer[{{ $i }}][phone]': {
            validators: {
              notEmpty: {
                message: 'Please insert phone number.'
              },
              numeric: {
                message: 'The value is not an numeric'
              }
            }
          },

          'emer[{{ $i }}][address]': {
            validators: {
              notEmpty: {
                message: 'Please insert address.'
              }
            }
          },
          <?php $i++; ?>
        <?php } ?>

      }
    })
  });
@endsection