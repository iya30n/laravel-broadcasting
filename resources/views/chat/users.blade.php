<!DOCTYPE html>
<html>
<head>
	<title>all users</title>
	<style>
	html, body {
		background-color: #fff;
		color: #636b6f;
		font-family: 'Raleway', sans-serif;
		font-weight: 100;
		font-size: 25px;
		height: 100vh;
		margin: 0;
	}
</style>
</head>
<body>
	<center>
		<ul style="margin-top:100px;">
			@foreach($users as $user)
				@if($user->id != auth()->user()->id)
			<li>
				<a href="{{ route('chat.user' , ['id'=>$user->id]) }}">{{ $user->name }}</a>
			</li>
				@endif
			@endforeach
		</ul>
	</center>
</body>
</html>