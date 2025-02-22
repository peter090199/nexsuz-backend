<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Request</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 8px; border: 1px solid #ddd;">

        <!-- Nexsuz Logo -->
        <div style="margin-bottom: 5px;">
            <img src="https://red-anteater-382469.hostingersite.com/public/nexsuzlogo.png" alt="Nexsuz Logo" style="max-width: 50px; height: 25px;">
        </div>

        <!-- Greeting -->
        <h4 style="font-size: 24px; color: #333; margin-bottom: 20px;">
            Hi {{ $data['fname'] }},
        </h4>
        
        <!-- Message -->
        <p style="font-size: 16px; color: #333; line-height: 1.6; margin-bottom: 20px;">
            We’ve received a request to reset the password for your Nexsuz account associated with <strong>{{ $data['email'] }}</strong>.
        </p>
        <p style="font-size: 16px; color: #333; line-height: 1.6; margin-bottom: 20px;">
            No changes have been made to your account yet.
        </p>
        <p style="font-size: 16px; color: #333; line-height: 1.6; margin-bottom: 20px;">
            You can reset your password by clicking the button below:
        </p>
        
        <!-- Reset Password Button -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="https://www.nexsuz.com/reset-password/{{ $data['email'] }}/{{ $data['token'] }}"
               style="background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; font-size: 16px; border-radius: 5px; font-weight: bold; display: inline-block;">
               Reset Password
            </a>
        </div>
        
        <!-- Support Message -->
        <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
            You can find answers to most questions and get in touch with us at <a href="https://support.nexsuz.com" style="color: #007bff; text-decoration: none;">support.nexsuz.com</a>, we're happy to assist you.
        </p>
        
        <!-- Nexsuz Team -->
        <p style="font-size: 16px; color: #333;">
            -The Nexsuz team.
        </p>

    </div>
</body>
</html>
