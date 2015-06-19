@extends('layout')

@section('content')
<form class="form-signin" method="POST" action="/auth/register">
    {!! csrf_field() !!}
    <h2 class="form-signin-heading">Please sign in</h2>
            <input type="email" placeholder="Email" name="email" class="form-control" value="{{ old('email') }}">
            <select class="form-control" name="city">
                @foreach(\DriveNowChecker\Watcher::$cities as $index => $city)
                    <option value="{{ $index }}">{{ $city }}</option>
                @endforeach
            </select>
            <input type="password" placeholder="Password" name="password" class="form-control" id="password">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember"> I am not a dick!
              </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
          </form>


</form>
@endsection