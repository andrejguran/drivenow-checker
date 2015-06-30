<h2>Found {{ count($cars) }} cars in {{ $watcher->distance }}min walking distance from {{ $watcher->address }}</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <td>Model name</td>
            <td>Distance</td>
            <td>Address</td>
            <td>Fuel level</td>
            <td>Fuel type</td>
        </tr>
    </thead>
    <tbody>
        @foreach($cars as $car)
        <tr>
            <td>{{ $car['model_name'] }}</td>
            <td>{{ round((int)$car['walking_distance'] / 60, 1) }}min</td>
            <td><a target="_blank" href="https://www.google.de/maps/dir/{{ urlencode($watcher->address) }}/{{ urlencode($car['address']) }}/">{{ $car['address'] }}</a></td>
            <td>{{ $car['fuel_level'] }}%</td>
            <td>{{ $car['fuel_type'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>