<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Background color */
        }

        .email-container {
            width: 100%;
            max-width: 600px; /* Adjust the max-width according to your design */
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff; /* Email content background color */
        }

        .email-content {
            padding-left: 20px;
            color: #333333; /* Text color */
        }

        .code {
            font-size: 24px; /* Adjust code font size */
            font-weight: bold;
            color: #007bff; /* Code color */
        }

        .greeting {
            margin-top: 20px;
            font-size: 24px; /* Adjust greeting font size */
            color: #333333; /* Text color */
        }

        .content {
            padding: 20px;
            margin-top: 20px;
            background-color: #8e9177; /* Content background color */
        }

        .content p {
            margin: 10px 0;
        }
    </style>
    <title>Password Reset Code</title>
</head>
<body>
    <div class="email-container">
        <div class="email-content">
            <p>Your verification code:</p>
            <h1 class="code">{{$code}}</h1>
        </div>
        <div class="content">
            @if($type == 'forgotpw')
                <h1 class="greeting">Hello, {{$name}}</h1>
                <p>We received a request to reset the password for your CipherDocs document titled "{{$title}}".</p>
                <p>To complete the password reset process, please use the verification code provided above.</p>
            @else
                <h1 class="greeting">Hello, {{$name}}</h1>
                <p>We received a request to access the document titled "{{$title}}".</p>
                <p>To complete the access process, please use the verification code provided above.</p>
            @endif
        </div>
    </div>
</body>
</html>