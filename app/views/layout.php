<!doctype html>
<html lang="{{ getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>Chat-Strike</title>

		<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css', true) }}"/>
	</head>
	
	<body>
		{%content%}

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				window.chatFetchDelay = {{ env('APP_DELAY_FETCH', 10000) }};
				window.chatOnlineDelay = {{ env('APP_DELAY_ONLINE', 10000) }};
				window.speakBetweenDelay = {{ env('APP_SPEAK_DELAY', 100) }};
				window.chatMaxBackgrounds = {{ $max_backgrounds ?? 1 }};
			});
		</script>
		<script src="{{ asset('js/app.js', true) }}"></script>
	</body>
</html>