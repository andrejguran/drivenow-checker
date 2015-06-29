@extends('layout')

@section('content')
    <p>Welcome home</p>
    <h2>Watchers</h2>

        <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Check period</th>
                  <th>Address</th>
                  <th>Max. fuel level</th>
                  <th>Model name</th>
                  <th>Fuel type</th>
                  <th>Max walking distance</th>
                  <th>Created at</th>
                  <th>Active</th>
                  <th>View</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
              <?php $i = 0; ?>
              @foreach($watchers as $watcher)
                <tr>
                  <th scope="row">{{ ++$i }}</th>
                  <td>{{ $watcher->refresh_period / 60}}min</td>
                  <td>{{ $watcher->address }}</td>
                  <td>{{ $watcher->fuel_level }}%</td>
                  <td>{{ $watcher->model_name or 'any' }}</td>
                  <td>{{ $watcher->fuel_type or 'any' }}</td>
                  <td>{{ $watcher->distance }}min</td>
                  <td>{{ $watcher->created_at }}</td>
                  <td><a class="btn @if ($watcher->on) btn-danger @else btn-success @endif" href="/toggle/{{$watcher->id}}" role="button">Turn @if ($watcher->on) off @else on @endif</a></td>
                  <td><a class="btn btn-info" href="/check/{{$watcher->id}}" role="button">View</a></td>
                  <td><a class="btn btn-primary" href="/delete/{{$watcher->id}}" role="button">Delete</a></td>
                </tr>
             @endforeach
              </tbody>
            </table>
            <hr>
            <p>
                <h3>Create new watcher</h3>
                 <form class="form-create" method="POST" action="/watcher">
                    {!! csrf_field() !!}

                    <select class="form-control" name="refresh_period">
                        <option value="">Please select check period</option>
                        @foreach(\DriveNowChecker\Watcher::$refreshPeriods as $secValue => $refreshPeriod)
                            <option value="{{ $secValue }}">{{ $refreshPeriod }}</option>
                        @endforeach
                    </select>

                    <input type="text" placeholder="Friedrichstraße 179, 10117 Berlin" name="address" class="form-control">

                    <input class="form-control" type="number" name="fuel_level" min="0" max="100" placeholder="Maximal fuel level (0-100)">

                    <select class="form-control" name="model_name">
                        @foreach(array_merge(['ANY'], \DriveNowChecker\Watcher::$modelNames) as $modelName)
                            <option value="{{ $modelName }}">{{ $modelName }}</option>
                        @endforeach
                    </select>

                    <select class="form-control" name="fuel_type">
                        @foreach(array_merge(['ANY'], \DriveNowChecker\Watcher::$fuelTypes) as $fuelType)
                            <option value="{{ $fuelType }}">{{ $fuelType }}</option>
                        @endforeach
                    </select>

                    <input class="form-control" type="number" name="distance" min="0" max="100" placeholder="Max walking distance in minutes (0-100)">

                    <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
                  </form>
            </p>
@endsection