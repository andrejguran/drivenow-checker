@extends('layout')

@section('content')
    <form class="form-signin" method="POST" action="/settings">
            {!! csrf_field() !!}
            <h2 class="form-signin-heading">Edit your settings</h2>
            <input type="text" placeholder="Email" name="email" class="form-control" value="{{ Auth::user()->email }}">
            <input type="password" placeholder="Password" name="password" class="form-control">
            <select class="form-control" name="city">
                @foreach(\DriveNowChecker\Watcher::$cities as $index => $city)
                    <option
                    @if ($index == Auth::user()->city)
                        selected="selected"
                    @endif
                     value="{{ $index }}">{{ $city }}</option>
                @endforeach
            </select>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Edit</button>
          </form>
@endsection