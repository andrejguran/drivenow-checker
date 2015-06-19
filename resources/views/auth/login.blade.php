@extends('layout')

@section('content')

<div class="row">
    <div class="col-md-12">
    <form class="form-signin" method="POST" action="/auth/login">
        {!! csrf_field() !!}
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" placeholder="Email" name="email" class="form-control" value="{{ old('email') }}">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" placeholder="Password" name="password" class="form-control" id="password">
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
      </form>

    </div>
</div>

@endsection