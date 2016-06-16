<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>New account details</title>
    </head>
    <body>
        <h1>Hello, {{$user->username}}</h1>
        <p>
            An account on {{ env('APP_URL') }} has been created for you with a temporary password: {{ $password }}.
            You can change your password after logging in under your profile.
        </p>
    </body>
</html>