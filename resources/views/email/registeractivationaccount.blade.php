<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Activate Your Account</title>
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
        
        <!-- Introduction Message -->
        <p style="font-size: 16px; color: #333; line-height: 1.6; margin-bottom: 20px;">
            Thank you for registering with Nexsuz. To activate your account, please click the button below:
        </p>
    
        <p style="font-size: 16px; color: #333; line-height: 1.6; margin-bottom: 20px;">
            Your activation code: <strong>{{ $data['code'] }}</strong>
        </p>
        
        <!-- Activation Button -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="https://www.nexsuz.com/activation/{{ $data['email'] }}"
               style="background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; font-size: 16px; border-radius: 5px; font-weight: bold; display: inline-block;">
               Activate My Account
            </a>
        </div>
    
        <!-- Support Message -->
        <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
            You can find answers to most questions and get in touch with us at <a href="https://support.nexsuz.com" style="color: #007bff; text-decoration: none;">support.nexsuz.com</a>, we're happy to assist you.
            -The Nexsuz team.
            
        </p>
    
    </div>
    
</body>
</html>
