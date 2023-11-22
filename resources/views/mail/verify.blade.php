<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
    </head>
    <body style="font-family: 'Roboto'; width:full; height:full; ">
        <div style="width: 100%; height: 100%; background-color: #DBE7FFe5; color:white; padding-top: 2rem; padding-bottom: 2rem;">
            <div style="margin: auto; width: 10%; font-weight: bold;">
                <img src="{{ $message->embed(public_path('/assets/logo.png')) }}" width="60px" height="60px" alt="logo icon"/>              
            </div>
            <div style="font-size: 22px; margin: 0 auto 30px auto; margin-top:2%; text-align: center; width: 100%; height:10%; color: black;">
                Your E-commerce
            </div>
            <div style="margin: 30px 3%; width: 48%; height:10%">
                <span style="color: black;">Hello, {{$user->name}}!</span>
            </div>
            <div style="margin: 30px 3%">
                <div>
                    <a style="background-color: rgb(96, 165, 250); border-radius: 4px; padding: 10px 30px; text-decoration:none; color:white;" 
                    href="{{config('app.front_url').'#/auth/?token='.$token.'&expires='.$expires.'&email='.$user->email}}">
                    Verify!</a>
                </div>
            </div>
            <div style="margin: 30px 3%; width: 80%;">
                <p style="color: black;">
                    Click the button above or copy and paste this link in the browser. 
                </p>
            </div>
            <div style="margin: 30px 3%; width: 80%; height: 20%;">
                <p style="word-wrap: break-word;">
                    <a href="{{config('app.front_url').'#/auth/?token='.$token.'&expires='.$expires.'&locale='.app()->getLocale().'&email='.$user->email}}" style="color: rgb(96, 165, 250);">
                        {{config('app.front_url').'#/auth/?token='.$token.'&expires='.$expires.'&email='.$user->email}}
                    </a>
                </p>
            </div>
            <div style="margin: 30px 3%; width: 80%;">
                <p style="color: black;">
                    if you have any questions or problems please let us help you through this address <span style="color: rgb(96, 165, 250);">support@moviequotes.ge</span>        
                </p>
            </div>
            <div style="margin: 30px 3%; width: 80%;">
                <p style="color: black;">
                    Your E-commerce Crew                
                </p>
            </div>
        </div>
    </body>
</html>