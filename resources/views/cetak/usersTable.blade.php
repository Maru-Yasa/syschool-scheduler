<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>Auto Generate Users's Table</title>
</head>
<body>
    
    <h3>{{ $batch_id }}</h3>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->password }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="text-muted">Created by Syschool-Scheduler Pro </p>

</body>
</html>