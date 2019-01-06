<!DOCTYPE html>
<html>

	<head>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>chat</title>
		<link href="{{asset('css/app.css')}}">
	</head>

	<body>
		<center>
			<h1> you are chating with: {{$user->name}}</h1><br><br>
			<input type="hidden" id="receiver" value="{{ $user->id }}" name="receiver">
			<input type="text" id="message" name="message"><button type="button" id="send">send</button>
		</center>
	</body>
	<script src="{{asset('js/app.js')}}"></script>

	<script>
		window.Laravel = {!! json_encode([
			'id' => auth() -> check() ? auth() -> user() -> id : null,
		])!!};
	</script>

	<script>
		$(document).ready(function () {
			// var id=$("#receiver").val();
			$("#send").click(function () {
				var receiver = $("#receiver").val();
				var message = $("#message").val();
				axios.post('/chat/send', {
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					receiver: receiver,
					message: message
				}).catch(function (error) {
					console.log(error);
				});
			});

			Echo.private('chat.' + window.Laravel.id).listen('ChatEvent', (e) => {
				console.log(e);
			});
		});
	</script>

</html>
{{--
.then(function (response) {
				console.log(response);
			})
			. --}}
