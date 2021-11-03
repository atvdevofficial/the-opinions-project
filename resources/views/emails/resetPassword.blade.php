<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Roboto Condensed', sans-serif;
        }
    </style>
</head>
<body>
    <p>Hi there,</p>
    <p>There was a request to reset your password!</p>
    <p>If you did not make this request then please ignore this email.</p>
    <p>Otherwise, please click this link to change your password:</p>
    <a href="http://{{ env('APP_URL', null) . "/resetPassword/${token}" }}" target="_blank">{{ env('APP_URL', null) . "/resetPassword/${token}" }}</a>
</body>
</html>
