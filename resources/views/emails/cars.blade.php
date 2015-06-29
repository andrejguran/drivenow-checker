<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
        @include('partials.carstable')
        <a href="{{ Illuminate\Support\Facades\Config::get('drivenowchecker.url') }}/toggle/{{ $watcher->id }}">Turn off this watcher</a>
	</body>
</html>