<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Request</title>
    <!-- Add Bootstrap CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h4>Hi {{ $data['fname'] }},</h4>
        <p>We’ve received a request to reset the password for your Nexsuz account associated with <strong>{{$data['email']}}</strong>.</p>
        <p>No changes have been made to your account yet.</p>
        
        <p>You can reset your password by clicking the button below:</p>
        <!-- Reset Password Button -->
        <a href="https://www.nexsuz.com/reset-password/{{$data['email']}}" class="btn btn-primary btn-lg">Reset Password</a>

        <br><br>

        <p>If you did not request a new password, please let us know immediately by replying to this email.</p>

        <p>Alternatively, you can sign in to your Nexsuz account below:</p>
        <!-- Sign In Button -->
        <a href="https://www.nexsuz.com/signInUI/{{$data['email']}}" class="btn btn-success btn-lg">Sign In</a>

        <br><br>

        <p>You can find answers to most questions and get in touch with us at <a href="https://support.nexsuz.com">support.nexsuz.com</a>. We’re happy to assist you.</p>
    </div>

    <!-- Optional Bootstrap JS and Popper.js (for full functionality if needed) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
