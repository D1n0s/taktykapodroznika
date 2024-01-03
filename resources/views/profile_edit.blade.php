@extends('layouts.app')

@section('content')
<div class="container py-5">
<div class="row">

      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
              <form method="get"  action="{{ route('profile.update') }}">
                  <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Imię</p>
              </div>
                <div class="col-sm-9 ">
                    <input type="text" class="form-control" id="phone" name="name" value="{{Auth::user()->name}}" />
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nazwisko</p>
              </div>
                <div class="col-sm-9 ">
                    <input type="text" class="form-control" id="phone" name="surname" value="{{Auth::user()->surname}}" data-inputmask-mask="[9]" />
                </div>
            </div>
            <hr>




            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
                <div class="col-sm-9 ">
                    <input type="text" class="form-control" id="phone" name="email" value="{{Auth::user()->email}}" data-inputmask-mask="[9]" />
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Numer telefonu</p>
              </div>
              <div class="col-sm-9 ">
                  <input type="text" class="form-control" id="phone" name="phone" value="{{Auth::user()->phone}}" data-inputmask-mask="[9]" />
              </div>
            </div>


              <hr>

                  <button type="submit"  class="btn btn-primary border-dark" style="background-color: green;">Zapisz</button>
                  <a type="submit" href="{{route('profile.cancel')}}"  class="btn btn-primary border-dark" style="background-color: red;margin-left: 1em;">Anuluj</a>

              </form>



            </div>
          </div>
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          {{ $error }}
                      @endforeach
                  </ul>
              </div>
          @endif
      </div>
        </div>

</div>


</div>
@endsection
