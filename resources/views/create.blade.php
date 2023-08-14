<!DOCTYPE html>
<html>
<head>
    <title>Create Form</title>
</head>
<body>
<h1>Create Form</h1>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('your-route') }}">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
    </div>
    <button type="submit">Submit</button>
</form>
</body>
</html>
