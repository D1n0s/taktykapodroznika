@extends('layouts.app')

@section('content')
<div class="container py-5">
<div class="row">

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body">


            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Imię</p>
              </div>
              <div class="col-2 text-truncat">
                  {{  Auth::user()->name  }}

              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nazwisko</p>
              </div>
                <div class="col-2 text-truncat">
                    {{  Auth::user()->surname  }}

                </div>
            </div>
            <hr>




            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
                <div class="col-2 text-truncat row">
                    {{  Auth::user()->email  }}

                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Numer telefonu</p>
              </div>
                <div class="col-2 text-truncat">
                    {{  Auth::user()->phone  }}

                </div>

{{--              <div class="col-sm-9 ">--}}
{{--                  <input type="text" class="form-control" id="phone" name="phone" placeholder="{{Auth::user()->phone}}" data-inputmask-mask="[9]" />--}}
{{--              </div>--}}
            </div>


              <hr>
              <a href="/profile_edit" class="btn btn-primary">Edytuj</a>



            </div>

          </div>
          @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif
          @if (session('info'))
              <div class="alert alert-info" style="background-color: lightyellow;">
                  {{ session('info') }}
              </div>
          @endif
        </div>

</div>


</div>
@endsection
